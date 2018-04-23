<?php

defined('MOODLE_INTERNAL') || die();
//require_once(__DIR__.'/../config.php');

function send_sendgrid_email($subject, $content, $receipients, $from_email, $from_name){
    global $CFG;
    $from = new SendGrid\Email($from_name, $from_email);
    $sg_content = new SendGrid\Content("text/html",$content);
    $to = new SendGrid\Email('',$receipients[0]);
    $mail = new SendGrid\Mail($from, $subject, $to, $sg_content);
    if (count($receipients)>1){
        for($i=1; $i<count($receipients); $i++){
            $mail->personalization[0]->addTo(new SendGrid\Email('', $receipients[$i]));
        }
    }
    $sg = new \SendGrid($CFG->sg_apikey);
    $response = $sg->client->mail()->send()->post($mail);
    echo $response->statusCode();
}

function convert_std_to_array($tl){
    $ts_arr = Array();
    foreach($tl as $t){
        $ts_arr[$t->id] = $t->name;
    }
    return $ts_arr;
}

function get_user_center_name($user_id=null){
    $userid = null;
    if ($user_id==null){
        global $USER;
        $userid = $USER->id;
    } else {
        $userid = $user_id;
    }
    global $DB, $CFG;
    $sql = <<<SQL
    select bic.name as name
    from {user_info_data} as uid join {branchadmin_centre_info} as bic on uid.data = bic.analysis_id
    where uid.userid = ? and uid.fieldid=?
SQL;
    $result = $DB->get_records_sql($sql, array($userid, $CFG->CENTER_FIELD_ID));
    $value = reset($result);
    return $value->name;
}

function get_user_batch_name($user_id=null){
    $userid = null;
    if ($user_id==null){
        global $USER;
        $userid = $USER->id;
    } else {
        $userid = $user_id;
    }
    global $DB, $CFG;
    $sql = <<<SQL
    select bic.name as name
    from {user_info_data} as uid join {branchadmin_ttbatches} as bic on uid.data = bic.analysis_id
    where uid.userid = ? and uid.fieldid=?
SQL;
    $result = $DB->get_records_sql($sql, array($userid, $CFG->BATCH_FIELD_ID));
    $value = reset($result);
    return $value->name;
}

function get_basic_student_info($username){
    global $DB;
    $user = $DB->get_record('user',array('username'=>$username));
    $user->batch = get_user_batch_name($user->id);
    $user->center = get_user_center_name($user->id);
    return $user;
}

function get_dlp_student_access(){
    //checks whether the student is dlp, if yes whether he has to video, booklets or not
    //check whether student is dlp
    global $DB, $USER;
    $sql = <<<EOT
    select uif.shortname, uid.data 
    from (select id, shortname from {user_info_field} where shortname in ('dlp', 'videoaccess', 'bookletaccess')) as uif 
    join 
    (select fieldid, data from {user_info_data} where userid=?) as uid 
    on uif.id=uid.fieldid 
EOT;
    $user_data = $DB->get_records_sql($sql, array($USER->id));
    $arr_access_control = Array();
    $access_control = new stdClass();
    foreach($user_data as $data){
        $arr_access_control[$data->shortname] = $data->data;
    }
    if (array_key_exists('dlp', $arr_access_control)){
        $access_control->dlp = $arr_access_control['dlp'];
    } else {
        $access_control->dlp = '0';
    }
    if (array_key_exists('videoaccess', $arr_access_control)){
        $access_control->videoaccess = $arr_access_control['videoaccess'];
    } else {
        $access_control->videoaccess = '1';
    }
    if (array_key_exists('bookletaccess', $arr_access_control)){
        $access_control->bookletaccess = $arr_access_control['bookletaccess'];
    } else {
        $access_control->bookletaccess = '1';
    }
    return $access_control;
}

function get_rao_user_profile_fields($profilefields, $user=null){
    //gets the specified user profile fields
    global $DB;
    if ($user==null){
      global $USER;
      $user = $USER;  
    }
    list($usql, $params) = $DB->get_in_or_equal($profilefields);
    $sql = <<<SQL
    select ud.id, ud.data, uif.shortname from
    {user} as u join (select * from {user_info_field} where shortname $usql) as uif join {user_info_data} as ud
    on
    u.id = ud.userid and ud.fieldid = uif.id and u.id=$user->id
SQL;
    $res = $DB->get_records_sql($sql, $params);
    $out = array();
    foreach($res as $record){
        //$fname = $record->shortname;
        $out[$record->shortname] = $record->data; 
    }
    return $out;
}

function get_rao_password($user){
    $dob = get_rao_user_profile_fields(array('birthdate'), $user);
    return $dob['birthdate'];
}

function set_profile_field($f_shortname, $userid, $newval){
    global $DB;
    //fetch the object if already exists
    $fetch_sql = <<<SQL
    select uid.* from {user_info_field} as uif join {user_info_data} as uid
    on uif.id = uid.fieldid where uif.shortname = ? and uid.userid= ?
SQL;
    $record = $DB->get_records_sql($fetch_sql,array($f_shortname, $userid));
    if (empty($record)){
        //create new record
        //fetch the field id
        $field = $DB->get_record('user_info_field',array('shortname'=>$f_shortname));
        $record = new stdClass();
        $record->userid = $userid;
        $record->fieldid = $field->id;
        $record->data = $newval;
        $DB->insert_record('user_info_data', $record);
    } else {
        $record = $record[array_keys($record)[0]];
        if ($record->data != $newval){
            $record->data = $newval;
            $DB->update_record('user_info_data', $record);
        }
    }
}

function is_branch_admin(){
    global $USER, $DB;
    $course_active = get_config('library','manager_course');
    $course_active = $DB->get_record('course',array('shortname'=>$course_active));
    //check if user is enrolled in the course
    $context = context_course::instance($course_active->id);
    if (is_enrolled($context, $USER->id, '', true)){
        return true;
    } 
    return false;
}

/*function get_jsonweb_request($url, $data){
    global $CFG;
    require_once($CFG->dirroot.'/vendor/autoload.php');
    //Unirest\Request::verifyPeer(false);
    //$headers = array('Accept' => 'application/json');
    //$body = Unirest\Request\Body::json($data);
    //$response = Unirest\Request::get($url, $headers, $data);
    //return $response->body;
    $client = new GuzzleHttp\Client();
    $res = $client->request('GET', $url);
    return $res->getBody()->getContents();
}*/

/*function post_web_request(){

}*/

function sendSMSLib(&$s_mobile, &$s_text){
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
        echo $success.PHP_EOL;
        return true;
    }
    if (strlen(trim($responseary[1])) != 0)	// errors
    {
        $error .= "Invalid/DND Numbers: " . trim($responseary[1]);
        echo $error.PHP_EOL;
        return false;
    }
}