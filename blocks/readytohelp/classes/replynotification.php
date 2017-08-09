<?php

//namespace block_readytohelp;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/blocks/readytohelp/locallib.php');

// Email alert when a user replies to an existing grievance
class block_readytohelp_replynotification extends \core\task\adhoc_task {
    
    public function execute(){
        $data = $this->get_custom_data();
        $email = $data->email;
        $subject = 'Student has raised a new query';
        $reply = $data->body;
        $replyurl = $data->replyurl;
        return $this->sendMail($email, $subject, $reply, $replyurl);
    }
    
    function sendMail($email, $subject, $reply, $replyurl){
        $mail = setup_phpmailer();                                   // TCP port to connect to
        $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
        );
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->setFrom('admin-noreply@raoiit.com', 'Grievance Portal');
        $mail->addAddress($email);
        $timestamp = date('Y-m-d :: G:i:s');

        $mail->Subject = $subject;
        $mail->Body = <<<HTML
        The student has replied to a grievance.<br>
        <h4>Reply: <pre>$reply</pre></h4>
        <p>To respond, click on the following link: </p>
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

