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
    from {user_info_data} as uid join {branchadmin_centre_info} as bic on uid.data = bic.id
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
    from {user_info_data} as uid join {branchadmin_ttbatches} as bic on uid.data = bic.id
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