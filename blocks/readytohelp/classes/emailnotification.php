<?php

//namespace block_readytohelp;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/blocks/readytohelp/locallib.php');

class block_readytohelp_emailnotification extends \core\task\adhoc_task {
    
    public function execute(){
        $data = $this->get_custom_data();
        $type = $data->type;
        $email = $data->email;
        $subject = $data->subject; // Grievance subject
        $description = $data->description; // Full grievance body
        $replyurl = $data->replyurl; // Url for responding
        return $this->sendMail($type, $email, $subject, $description, $replyurl);
    }
    
    function sendMail($type, $email, $subject, $description, $replyurl){
        $mail = setup_phpmailer();                                  // TCP port to connect to
        $mail->SMTPOptions = array(
            'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
            )
        );
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->setFrom('edumate-noreply@raoiit.com', 'Grievance Portal');
        if (is_array($email)){
            foreach($email as $addr){
                $mail->addAddress($addr);
            }
        } else{
            $mail->addAddress($email);
        }
        


        // Set content according to type of email
        switch ($type) {
            case 'new_grievance':
                $mail->Subject = "New Grievance - ".$subject;
                $intro = 'A new issue raised by a student needs your attention.';
                break;
            case 'reminder';
                $mail->Subject = "[Reminder] Grievance - ".$subject;
                $intro = 'This grievance needs your attention.';
                break;
            case 'admin-notification':
                $mail->Subject = 'New Grievance on Edumate - '.$subject;
                $intro = '';
                break;
            default:
                $mail->Subject = "[Reminder] Grievance - ".$subject;
                break;
        }
        $timestamp = date('Y-m-d :: G:i:s');

        $mail->Body = <<<HTML
        $intro<br>
        <h4>Students query:</h4>
        <p>Subject: <pre>$subject</pre></p>
        <p>Description: <pre>$description</pre></p>
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
