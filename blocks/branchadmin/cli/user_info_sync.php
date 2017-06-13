<?php

define('CLI_SCRIPT',true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->libdir.'/moodlelib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');


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
$user_list = $DB->get_records('user',array('auth'=>'db'));

foreach($user_list as $user){
    //get the info of this user 
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
    profile_save_data($user_profile);
    cli_write($user->username.'-updated\n');
}