<?php

function send_leave_request($data){
    global $CFG;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $CFG->django_server.'/faculty/apply_leave/'); //set the url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //return as a variable
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle
    if( $response )
        return json_decode($response);
    else{
        echo "Error getting papers";
        return array();
    }

}

function apply_leave($date, $reason, $is_half_day = False){
    global $USER;
    $leave_data = array();
    $leave_data['date'] = $date;
    $leave_data['reason'] = $reason;
    if ($is_half_day){
        $leave_data['leave_type'] = 'H';
    } else {
        $leave_data['leave_type'] = 'F';
    }
    $leave_data['user'] = $USER->username;
    $ret = send_leave_request($data);
}

function apply_od($date, $reason, $stime, $etime){
    global $USER;
    $leave_data = array();
    $leave_data['date'] = $date;
    $leave_data['reason'] = $reason;
    $leave_data['leave_type'] = 'OD';
    $leave_data['start_time'] = $stime;
    $leave_data['end_time'] = $etime;
    $leave_data['user'] = $USER->username;
    $ret = send_leave_request($data);
}

