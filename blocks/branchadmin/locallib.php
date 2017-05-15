<?php

require_once('../../config.php');

/*
gets users who belong to the same center as the current user
*/
function get_user_center(){
    global $USER;
    if ($USER->id==null){
        return '';
    }
    $user = new stdClass();
    $user->id = $USER->id;
    profile_load_data($user);
    return $user->profile_field_center;
}

function get_batches_for_user($center){
    //gets the names of all batches with the current user
    //initialize curl handle
    $url = 'http://analysis.raoiit.com/app/edumate/get_batches_by_center.php?center='.$center;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //set the url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //return as a variable
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle
    if( $response )
        return json_decode($response);
    else{
        echo "Error getting batches";
        return array();
    }

    profile_load_data($USER);
    $sql = 'select * from {user} as u join {user_info_data} as ud where ud.data like ?';
    $users_by_center = $DB->get_records_sql($sql,array($USER->profile_field_center));
    return $users_by_center;
}
