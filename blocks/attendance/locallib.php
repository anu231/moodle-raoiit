<?php

require_once('../../config.php');

function get_attendance_records($start_date, $end_date, $user='self'){
    global $USER;
    global $CFG;
    if ($user == 'self'){
        $user = $USER->username;
    }
    $ch = curl_init();
    $url = $CFG->biometric_record_url.'userid='.$user.'&sdate='.$start_date.'&edate='.$end_date;
    // Disable SSL verification
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // Will return the response, if false it print the response
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Set the url
    curl_setopt($ch, CURLOPT_URL,$url);
    // Execute
    $result=curl_exec($ch);
    // Closing
    curl_close($ch);
    return json_decode($result,true);
}