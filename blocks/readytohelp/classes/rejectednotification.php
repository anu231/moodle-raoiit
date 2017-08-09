<?php

// Email sent when the response is rejected.

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/blocks/readytohelp/locallib.php');

class block_readytohelp_rejectednotification extends \core\task\adhoc_task {
    
    public function execute(){
        $data = $this->get_custom_data();
        $email = $data->email;
        $subject = $data->subject;
        $description = $data->description;
        $response = $data->response; // Mods response
        $replyurl = $data->replyurl; // Url for new response
        return $this->sendMail($email, $subject, $description, $response, $replyurl);
    }
    
    function sendMail($email, $subject, $description, $response, $replyurl){
        $$mail = setup_phpmailer();
        $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
        );
        $mail->isHTML(true);         // Set email format to HTML
        $mail->setFrom('admin-noreply@raoiit.com', 'Grievance Portal');
        $mail->addAddress($email);
        $timestamp = date('Y-m-d :: G:i:s');

        $mail->Subject = "Grievance Response Rejected";
        $mail->Body = <<<HTML
        Your response to a query was rejected.<br>
        <h4>Students query:</h4>
        <p>Subject: <pre>$subject</pre></p>
        <p>Description: <pre>$description</pre></p>
        <hr>
        <h4>Your Response:</h4>
        <pre>$response</pre>
        <p>To respond, click on the following link:</p>
        <a href='$replyurl'>$replyurl</a>
        <hr>
        <small>Note: This is an automated email. Do not reply to this email.</small>
HTML;

        $mail->AltBody = $mail->Body;

        if(!$mail->send()) {
            echo 'Message could not be sent.';
            echo 'Mailer Error: ' . $mail->ErrorInfo;
            return 1;
        } else {
            return -1;
        }
    }
    
}
