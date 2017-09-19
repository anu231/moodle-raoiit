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
    $qry = "select * from userinfo where userid=".$username;
    $res = $link->query($qry);
    if (!$res){
        return false;
    }
    $row= $res->fetch_assoc();
    $row['ttbatchid'] = $batch_map[$row['ttbatchid']];
    $row['centre'] = $branch_map[$row['centre']];
    return $row;
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

function sync_moodle_field($analysis_field, $moodle_data, $analysis_fdata, $userid){
    global $DB, $field_map;
    $moodle_field = $field_map[$analysis_field];
    if (array_key_exists($moodle_field, $moodle_data)){
        //check for equality
        if ($moodle_data[$moodle_field]->data != $analysis_fdata){
            //update moodle data
            //echo $moodle_data[$moodle_field]->data.':'.$analysis_fdata.PHP_EOL;
            $elem = new stdClass();
            $elem->id = $moodle_data[$moodle_field]->dataid;
            $elem->data = $analysis_fdata;
            $DB->update_record('user_info_data',$elem);
            return 'updated';
            //echo $userid.':'.$analysis_field.':updated:'.$analysis_fdata;
            //cli_write($userid.':'.$analysis_field.':updated:'.$analysis_fdata);
        }else {
            return 'same';
        }
    }else {
        //need to create a new record
        $elem = new stdClass();
        $elem->fieldid = $moodle_field;
        $elem->data = $analysis_fdata;
        $elem->userid = $userid;
        $DB->insert_record('user_info_data',$elem);
        return 'created';
        //cli_write($userid.':'.$analysis_field.':created:'.$analysis_fdata);
        //echo $userid.':'.$analysis_field.':created:'.$analysis_fdata;
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
    foreach($field_map as $key=>$value){
        if ($user_data[$key] == 0){
            continue;
        }
        //cli_write($user_data[$key].PHP_EOL);
        $ret = sync_moodle_field($key, $moodle_d, $user_data[$key], $userid);
        if (!constant('CLI_SCRIPT')){
            echo $userid.':'.$key.':'.$ret.':'.$user_data[$key].'<br>';
        } else {
            cli_write($userid.':'.$key.':'.$ret.':'.$user_data[$key].PHP_EOL);
        }
    }
}

global $DB;
//get all the users aith auth == db 
$user_list = array();
if (!constant('CLI_SCRIPT')){
    echo 'CLI_SCRIPT false'.'<br>';
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
    sync_user_data_raw_sql($user->username, $user->id);
}
close_analysis_db($link);