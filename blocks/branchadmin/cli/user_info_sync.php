<?php
if ((isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == '203.123.46.194' || $_SERVER['REMOTE_ADDR'] == '192.168.1.19'))
 || (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == '203.123.46.194')){
    define('CLI_SCRIPT',false);
} else{
    define('CLI_SCRIPT',true);
}
echo constant('CLI_SCRIPT');
//exit;
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once($CFG->dirroot.'/blocks/timetable/locallib.php');
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');

//require_once('../locallib.php');
$branch_map = load_field_records_edumate('branchadmin_centre_info');
$batch_map = load_field_records_edumate('branchadmin_ttbatches');
$field_map = array(
    'mobilenumber'=>12,
    'mobilefather'=>14,
    'mobilemother'=>16,
    'centre'=>57,
    'ttbatchid'=>58
);

function get_user_data_analysis($username){
    //gets the users data from the analysis server
    global $CFG, $branch_map, $batch_map,$link;
    //$link = connect_analysis_db();
    $qry = "select * from userinfo where userid=".$username;
    $res = $link->query($qry);
    if (!$res){
        return false;
    }
    $row= $res->fetch_assoc();
    $row['ttbatchid'] = $batch_map[$row['ttbatchid']];
    $row['centre'] = $branch_map[$row['centre']];
    //close_analysis_db($link);
    return $row;
}

function get_moodle_id($username){
    global $DB;
    $user_entry = $DB->get_record('user',array('username'=>$username));
    if (!$user_entry){
        return false;
    }
    return $user_entry->id;
}

function get_user_course_type($user){
    //course types - 1 year , 2 year, repeater
    $ctype = 'None';
    if ($user['targetyear'] == '2019'){
        $ctype = '2 Year Program';
    } else if ($user['targetyear'] == '2018'){
        //check batch
        if ($user['batch'] == '0' || $user['batch'] == '2'){
            if (intval($user['userid'])>=800000){
                //1 year program
                $ctype = '1 Year Program';
            } else{
                $ctype = '2 Year Program';
            }
        } else if ($user['batch'] == '1' || $user['batch'] == '3' || $user['batch'] == '10'){
            $ctype = 'Repeater';
        }
    }
    return $ctype;
}

function get_user_data_moodle($username){
    global $DB;
    $sql = <<<EOT
    select ufi.shortname as name, ud.data as data, ufi.id as fieldid, ud.id as dataid from 
    {user} as u join {user_info_data} as ud join {user_info_field} as ufi 
    on u.id = ud.userid and ud.fieldid = ufi.id 
    where ufi.shortname in ('studentmobile','fathermobile','mothermobile',
    'center', 'batch') and u.username=?
EOT;
    $res = $DB->get_records_sql($sql,array($username));
    $ret = Array();
    foreach($res as $row){
        $ret[$row->fieldid] = $row;
    }
    return $ret;
}

function sync_user_data_analysis($username){
    $user_data = get_user_data_analysis($username);
    if (!$user_data){
        return 'User DNE - '.$username;
    }
    $user_profile = new stdClass();
    $user_profile->id = get_moodle_id($username);
    if (!$user_profile->id){
        return 'USER DNE - '.$username;
    }
    //cli_write($user->username.'-starting\n');
    global $batch_map, $branch_map;
    profile_load_data($user_profile);
    if ($user_data['ttbatchid']!=0){
        $user_profile->profile_field_batch = $batch_map[$user_data['ttbatchid']];
    }
    if ($user_data['centre']!=0){
        $user_profile->profile_field_center = $branch_map[$user_data['centre']];
    }
    $user_profile->profile_field_fathername = $user_data['fathername'];
    $user_profile->profile_field_birthdate = $user_data['birthdate'];
    $user_profile->profile_field_studentmobile = $user_data['mobilenumber'];
    $user_profile->profile_field_fathermobile = $user_data['mobilefather'];
    $user_profile->profile_field_mothermobile = $user_data['mobilemother'];
    $user_profile->profile_field_coursetype = get_user_course_type($user_data);
    //print_r($user_profile);
    profile_save_data($user_profile);
    return $user_data['ttbatchid'].'_'.$user_data['centre'].'_'.$user_profile->profile_field_coursetype;
}

function sync_moodle_field($analysis_field, $moodle_data, $analysis_fdata, $userid){
    global $DB, $field_map;
    $moodle_field = $field_map[$analysis_field];
    if (array_key_exists($moodle_field, $moodle_data)){
        //check for equality
        if ($moodle_data[$moodle_field]!=$analysis_fdata){
            //update moodle data
            $elem = new stdClass();
            $elem->id = $moodle_data[$moodle_field]->dataid;
            $elem->data = $analysis_fdata;
            $DB->update_record('user_info_data',$elem);
            cli_write($userid.':'.$analysis_field.':updated:'.$analysis_fdata);
        }
    }else {
        //need to create a new record
        $elem = new stdClass();
        $elem->fieldid = $moodle_field;
        $elem->data = $analysis_fdata;
        $elem->userid = $userid;
        $DB->insert_record('user_info_data',$elem);
        cli_write($userid.':'.$analysis_field.':created:'.$analysis_fdata);
    }
}

function sync_user_data_raw_sql($username, $userid){
    global $branch_map;
    global $batch_map, $DB, $field_map;
    $user_data = get_user_data_analysis($username);
    //$userid = get_moodle_id($username);
    if (!$user_data){
        return 'User DNE - '.$username;
    }
    //load user details in moodle
    $moodle_d = get_user_data_moodle($username);
    foreach($field_map as $fm=>$value){
        sync_moodle_field($fm, $moodle_d, $user_data[$fm], $userid);
    }
}

global $DB;
//get all the users aith auth == db 
$user_list = array();
if (!constant('CLI_SCRIPT')){
    echo 'CLI_SCRIPT false';
    if (isset($_GET['username'])) {
        $t = new stdClass();
        $t->username = $_GET['username'];
        $t = $DB->get_record('user', array('username'=>$_GET['username']));
        array_push($user_list,$t);
    } else{
        echo 'No username supplied';
    }
} else{
    echo 'loading from db';
    $user_list = $DB->get_records('user',array('auth'=>'db'));
}
$link = connect_analysis_db();
foreach($user_list as $user){
    //get the info of this user
    /* 
    $user_data = get_user_data($user->username);
    $user_profile = new stdClass();
    $user_profile->id = $user->id;
    cli_write($user->username.'-starting\n');
    profile_load_data($user_profile);
    $user_profile->profile_field_batch = $user_data['ttbatchid'];
    $user_profile->profile_field_center = $user_data['centre'];
    $user_profile->profile_field_fathername = $user_data['fathername'];
    $user_profile->profile_field_birthdate = $user_data['birthdate'];
    $user_profile->profile_field_studentmobile = $user_data['mobilenumber'];
    $user_profile->profile_field_fathermobile = $user_data['mobilefather'];
    $user_profile->profile_field_mothermobile = $user_data['mobilemother'];
    profile_save_data($user_profile);*/
    //$ret = sync_user_data_analysis($user->username);
    sync_user_data_raw_sql($user->username, $user->id);
    if (constant('CLI_SCRIPT')){
        cli_write($user->username.'-updated\n'.PHP_EOL);
    } else{
        echo $user->username.'-updated\n';
        echo 'Center Updated to - ';
    }
}
close_analysis_db($link);