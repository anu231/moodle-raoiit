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
    $ret = send_leave_request($leave_data);
}

// Multiple days //

function md_leave($sdate, $edate, $reason){
    global $USER;
    $leave_data = array();
    $leave_data['sdate'] = $sdate;
    $leave_data['edate'] = $edate;
    $leave_data['reason'] = $reason;
    $leave_data['user'] = $USER->email;
    $ret = send_leave_request($leave_data);
}

// Multiple days //

function apply_od($date, $reason, $stime, $etime){
    global $USER;
    $leave_data = array();
    $leave_data['date'] = $date;
    $leave_data['reason'] = $reason;
    $leave_data['leave_type'] = 'OD';
    $leave_data['start_time'] = $stime;
    $leave_data['end_time'] = $etime;
    $leave_data['user'] = $USER->email;
    $ret = send_leave_request($leave_data);
   
}


function faculty_detail($email){
        global $CFG;
        $ch = curl_init();
        $header = array(
        'Authorization: Token 390aa78baf9ef435ec92427e1fea9323133f5ce9'
        );
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,"http://192.168.1.19:8000/timetable/faculty/search_by_mail/?email=$email");
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        $response=curl_exec($curl);
        curl_close($curl);
            if($response){
                $rec = json_decode($response);
                $empid = $rec->empid;

                return $empid;
            }
                else{
                echo "Error getting papers";
                return array();
            }

}





//
function faculty_attendance($email){
        global $CFG;
        $ch = curl_init();
        $header = array(
        'Authorization: Token 390aa78baf9ef435ec92427e1fea9323133f5ce9'
        );
        $curl=curl_init();
        curl_setopt($curl,CURLOPT_URL,"http://192.168.1.19:8000/timetable/faculty/search_by_mail/?email=$email");
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($curl,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
        $response=curl_exec($curl);
        curl_close($curl);
            if($response){
                $rec = json_decode($response);
                $id = $rec->id;
                return $id;
            }
                else{
                echo "Error getting papers";
                return array();
            }

}

function faculty_attendance_detail($id,$from,$to){
        global $CFG;
            $ch = curl_init();
            $header = array(
            'Authorization: Token 390aa78baf9ef435ec92427e1fea9323133f5ce9'
            );
            $curl=curl_init();
            curl_setopt($curl,CURLOPT_URL,"http://192.168.1.19:8000/timetable/faculty/$id/attendance_details/?from=$from&to=$to");
            curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
            curl_setopt($curl,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($curl,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,false);
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
            $response=curl_exec($curl);
            curl_close($curl);
                if($response){
                    $rec = json_decode($response);
                  // print_r ($rec);
                    return $rec;
                }
                    else{
                    echo "Error getting papers";
                    return array();
                }
}
