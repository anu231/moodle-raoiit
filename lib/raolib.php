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