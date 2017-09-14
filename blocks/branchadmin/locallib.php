<?php

require_once(__DIR__.'/../../config.php');


/*
gets users who belong to the same center as the current user
*/
function get_user_center($userid=null){
    $user_id = null;
    if ($userid==null){
        global $USER;
        $user_id = $USER->id;
    } else{
        $user_id = $userid;
    }
    $user = new stdClass();
    $user->id = $user_id;
    profile_load_data($user);
    return $user->profile_field_center;
}

function get_center_obj($centre_name){
    global $DB;
    $center = $DB->get_records_sql('select * from {branchadmin_centre_info} where name like ? and status=1',array($centre_name));
    if (count($center)>=0){
        return $center[key($center)];
    } else {
        return false;
    }
}

function get_batch_name($batchid){
    global $DB;
    $batchname = $DB->get_record('branchadmin_ttbatches',array('id'=>$batchid));
    return $batchname->name;
}

function get_center_name($cid){
    global $DB;
    $cname = $DB->get_record('branchadmin_centre_info',array('id'=>$cid));
    return $cname->name;
}

function get_batches_for_user($center){
    //gets the names of all batches with the current user
    //initialize curl handle
    //get centre object
    global $DB;
    $centre_obj = get_center_obj($center);
    if (!$centre_obj){
        return false;
    }
    $batches = $DB->get_records_menu('branchadmin_ttbatches',array('centreid'=>$centre_obj->id,'status'=>'1'),null,'id,name');
    return $batches;
}

function get_batch_names($batches){
    global $DB;
    $batch_ids = $DB->get_records_list('branchadmin_ttbatches','id',$batches,null,'name');
    $bids = array();
    foreach($batch_ids as $bid){
        array_push($bids,$bid->name);
    }
    return $bids;
}

function get_userids_by_batch($batches){
    global $DB;
    //get all the ids with the specified batches
    $userids = $DB->get_records_list('user_info_data','data',get_batch_names($batches),null,'userid');
    $userid_list = array();
    foreach($userids as $userid){array_push($userid_list, $userid);}
    return $userid_list;
}

function fetch_numbers_for_userids($userid_list){
    global $DB;
    //fetch the ids for mobile numnbers
    $field_ids = $DB->get_records_list('user_info_field','name',array('studentmobile','fathermobile','mothermobile'),null,'id');
    $field_id_list = array();
    foreach($field_ids as $fi){array_push($field_id_list,$fi);}
    foreach($userid_list as $userid){
        $data = $DB->get_records_list('user_info_data','fieldid',$field_id_list,null,'data');
    }
}

function fetch_numbers_for_userid($userid){
    global $DB;
    
}

function sendSMS(&$s_mobile, &$s_text){
        //initialize the request variable
        $success = '';
        $error = '';
        $request = "";
        //this is the key of our sms account
        $param["workingkey"] = "3693f2jl9yh7375b0o1i";
        //this is the message that we want to send
        $param["message"] = stripslashes($s_text);
        //these are the recipients of the message
        $param["to"] = $s_mobile;
        //this is our sender
        $param["sender"] = "RAOIIT";

        //traverse through each member of the param array
        foreach($param as $key=>$val){
            //we have to urlencode the values
            $request.= $key."=".urlencode($val);
            //append the ampersand (&) sign after each paramter/value pair
            $request.= "&";
        }
        //remove the final ampersand sign from the request
        $request = substr($request, 0, strlen($request)-1); 

        //this is the url of the gateway's interface
        $url = "http://alerts.prioritysms.com/api/web2sms.php";

        //initialize curl handle
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //set the url
        curl_setopt($ch, CURLOPT_POST, count($param)); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 	
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        $response = curl_exec($ch); //run the whole process and return the response
        curl_close($ch); //close the curl handle

        $responseary = explode("Invalid/DND Numbers:", $response);

        if (substr(trim($responseary[0]), 0, 12) == "Message GID=") // delivered successfully
        {
            $successary = explode(",", trim($responseary[0]));
            $success .= "Message successfully delivered to " . sizeof($successary) . " mobile numbers.";
            return true;
        }
        if (strlen(trim($responseary[1])) != 0)	// errors
        {
            $this->error .= "Invalid/DND Numbers: " . trim($responseary[1]);
            return false;
        }
    }

    function get_user_center_batch_analysis($userid, $link){
        /*
        assumes the connection to analysis db has already been made
        */
        $sql = 'select centre, ttbatchid from userinfo where userid='.$userid;
        $res = $link->query($sql);
        if(!$res){
            return False;
        }
        $row = $res->fetch_assoc();
        if(!$row){return False;}
        else {return $row;}
    }


/***************************************STUDENT PERFORMANCE RELATED FUNCTIONS****************/
function get_student_token($userid){
    global $CFG;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $CFG->django_server.'api-auth-token',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            'username' => $userid,
            'auth_id' => $CFG->django_auth_id
        )
    ));
    $resp = curl_exec($ch);
    curl_close($curl);
    if ($resp){
        return $resp;
    } else{
        return curl_error($ch);
    }
}

function get_student_full_report($userid){
    global $CFG;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $CFG->django_server.'student/spr?auth_id='.$CFG->django_auth_id.'&username'.$userid,
        CURLOPT_RETURNTRANSFER => 1,
    ));
    $resp = curl_exec($ch);
    if ($resp){
        return $resp;
    } else{
        $msg = new stdClass();
        $msg->error = curl_error($ch);
        return $msg;
    }

}
/********************************************************************************************/