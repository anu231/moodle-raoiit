<?php
//namespace SendGrid;
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once(__DIR__.'/../../branchadmin/locallib.php');
global $CFG;
require_once($CFG->dirroot.'/vendor/autoload.php');

$courses = Array('30');
$student_data = notification_filter(Array('courses'=>$courses));

//$email = new \SendGrid\Mail\Mail(); 
$apiKey = "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";
$sg = new SendGrid($apiKey);

$from = new SendGrid\Email("Anurag", "anu231@gmail.com");
$subject = "Rao Representatives: JEE Main 2019 Registration";
$to = new SendGrid\Email('Anurag','anurag.sharma@raoiit.com');
$content = new SendGrid\Content("text/html","TEST EMAIL");
$mail = new SendGrid\Mail($from, $subject, $to, $content);
$mail->personalization[0]->addCc(new SendGrid\Email('','abhishek.pawar@raoiit.com'));
$response = $sg->client->mail()->send()->post($mail);
var_dump($response);
/*$email->setFrom("test@example.com", "Example User");
$email->setSubject("Sending with SendGrid is Fun");
$email->addTo("test@example.com", "Example User");
$email->addContent("text/plain", "and easy to do anywhere, even with PHP");
$email->addContent(
    "text/html", "<strong>and easy to do anywhere, even with PHP</strong>"
);*/