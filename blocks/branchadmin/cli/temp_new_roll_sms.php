<?php
/*
Sends roll number by sms to the new students
*/

define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
global $CFG, $DB;
require_once($CFG->libdir.'/raolib.php');

$users = $DB->get_records('user', array('lastaccess'=>0,'auth'=>'db'));
$cnt = 1;
$total = count($users);
foreach($users as $user){
    if (count($user->username) != 8){
        continue;
    }
    //get the dob
    $dob = get_rao_user_profile_fields(array('birthdate'), $user);
    if (empty($dob)){
        echo 'ERROR - Birtdate NULL'.PHP_EOL;
        continue;
    }
    $dob = $dob['birthdate'];
    //get the numbers
    $number_details = get_rao_user_profile_fields(array('studentmobile','fathermobile','mothermobile'), $user);
    $sms_text = <<<SMS
    Dear Student,
    Your RAO Academy roll number is $user->username. Your default password for https://edumate.raoiit.com is - $dob
SMS;
    //echo $sms_text.PHP_EOL;
    //print_r($number_details);
    //$test_number = '9892138542';
    foreach($number_details as $number){
        if ($number != ''){
            sendSMSLib($number, $sms_text);
        }
    }
    $cnt = $cnt+1;
    echo $cnt.'/'.$total.' '.$user->username.PHP_EOL;
}