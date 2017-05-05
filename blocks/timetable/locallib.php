<?php

/*function get_timetable(){
    //  Initiate curl
    $ch = curl_init();
    $url = 'http://192.168.1.161/moodle/timetable.php?id='.$_SESSION['USER']->username;
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
}*/

