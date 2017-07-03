<?php
if ($_SERVER['REMOTE_ADDR'] == '203.123.46.194' || $_SERVER['REMOTE_ADDR'] == '192.168.1.19' || $_SERVER['HTTP_X_FORWARDED_FOR'] == '203.123.46.194'){
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
//require_once('../locallib.php');

function get_user_data_analysis($username){
    //gets the users data from the analysis server
    global $CFG;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $CFG->analysis_user_info_url.'?userid='.$username); //set the url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //return as a variable
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle
    if( $response )
        return json_decode($response, true);
    else{
        echo "Error getting data from :".$url;
        return array();
    }
}

function get_moodle_id($username){
    global $DB;
    $user_entry = $DB->get_record('user',array('username'=>$username));
    if (!$user_entry){
        return false;
    }
    return $user_entry->id;
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
    profile_load_data($user_profile);
    $user_profile->profile_field_batch = $user_data['ttbatchid'];
    $user_profile->profile_field_center = $user_data['centre'];
    $user_profile->profile_field_fathername = $user_data['fathername'];
    $user_profile->profile_field_birthdate = $user_data['birthdate'];
    $user_profile->profile_field_studentmobile = $user_data['mobilenumber'];
    $user_profile->profile_field_fathermobile = $user_data['mobilefather'];
    $user_profile->profile_field_mothermobile = $user_data['mobilemother'];
    profile_save_data($user_profile);
    return $user_data['ttbatchid'].'_'.$user_data['centre'];
}

function get_user_data($userid){
    //gets the users data from the analysis server
    global $CFG;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $CFG->analysis_user_info_url.'?userid='.$userid); //set the url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //return as a variable
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle
    if( $response )
        return json_decode($response, true);
    else{
        echo "Error getting data from :".$url;
        return array();
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
        array_push($user_list,$t);
    } else{
        echo 'No username supplied';
    }
} else{
    echo 'loading from db';
    //$user_list = $DB->get_records('user',array('auth'=>'db'));
}

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
    $ret = sync_user_data_analysis($user->username);
    if (constant('CLI_SCRIPT')){
        cli_write($user->username.'-updated\n');
    } else{
        echo $user->username.'-updated\n';
        echo 'Center Updated to - '.$ret;
    }
}