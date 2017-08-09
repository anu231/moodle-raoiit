<?php

// Email sent when the response is rejected.

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/blocks/readytohelp/locallib.php');

// Notify student that there is a new response to his query
class block_readytohelp_studentnotification extends \core\task\adhoc_task {
    
    public function execute(){
        $data = $this->get_custom_data();
        $email = $data->email;
        $response = $data->response; // Approved Mods response
        $grievance = $data->grievance;
        $viewurl = $data->viewurl; // Url for new response
        return $this->sendMail($email, $grievance, $viewurl);
    }
    
    function sendMail($email, $grievance, $viewurl){
        $mail = setup_phpmailer();
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

        $mail->Subject = "New Response to Your Grievance";
        $mail->Body = <<<HTML
        There is a new response to your grievance.<br>
        <h4>Your query:</h4>
        <p>Subject: <pre>$grievance->subject</pre></p>
        <p>Description: <pre>$grievance->description</pre></p>
        <hr>
        <p>Click on the following link to view the response:</p>
        <a href='$viewurl' target="_blank"><b><u>View Response</u></b></a>
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
