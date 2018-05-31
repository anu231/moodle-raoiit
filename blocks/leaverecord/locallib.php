<?php

function send_leave_request($data){
    global $CFG;
    $ch = curl_init();
    $header = array(
        'Authorization: Token 390aa78baf9ef435ec92427e1fea9323133f5ce9'
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $CFG->django_server.'/leaves/apply/'); //set the url
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
        $leave_data['type'] = 'H';
    } else {
        $leave_data['type'] = 'F';
    }
    $leave_data['user'] = $USER->email;
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
    $leave_data['user'] = $USER->email;
    $ret = send_leave_request($data);
}

