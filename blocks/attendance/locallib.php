<?php

require_once('../../config.php');

function get_attendance_records($start_date, $end_date, $user='self'){
    global $USER;
    global $CFG;
    if ($user == 'self'){
       echo $user = $USER->username;
    }
      $user = $USER->username;
    $ch = curl_init();
    $a="http://biometric.raoiit.com:8081/attendance/biometric-test.php?";
    $url = $a.'username='.$user.'&startdate='.$start_date.'&enddate='.$end_date;
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