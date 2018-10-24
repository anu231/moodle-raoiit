<?php

require_once('../../config.php');
// curl function used for fetching data from biometric server
function get_attendance_records($start_date, $end_date, $user='self'){
    global $USER;
    global $CFG;
    if ($user == 'self'){
       $user = $USER->username;
    }
    $user = $USER->username;
    $ch = curl_init();
    $a="http://biometric.raoiit.com:8081/attendance/biometric-test.php?"; // biometric machin url
    $url = $a.'username='.$user.'&startdate='.$start_date.'&enddate='.$end_date; // parameter passing with above url
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