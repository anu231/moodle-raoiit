<?php


defined('MOODLE_INTERNAL') || die();

/**
 * A collection of 20 emails to be sent
 */
class local_raomanager_notificationemails extends \core\task\adhoc_task {
    public function execute(){
        $data = $this->get_custom_data();
        $recipients = $data->emails; // Array emails[20]
        $subject = $data->subject;
        $body = $data->body;
        $recipients = array('akshay.handrale@raoiit.com', 'akshay.handrale@raoiit.com'); // todo Remove in prod
        foreach ($recipients as $recipient) {
            // Send email
            $mail = new PHPMailer;
            // $mail->SMTPDebug = 3;                               // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'mail.raoiit.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'admin-noreply@raoiit.com';                 // SMTP username
            $mail->Password = 'v1Bdypg0';                           // SMTP password
            // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
            );
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->setFrom('admin-noreply@raoiit.com', 'RaoIIT Notification');

            // Set data
            $mail->addAddress($recipient);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AltBody = $mail->Body;

            if(!$mail->send()) {
                echo "$recipient failed";
                echo 'Reason: ' . $mail->ErrorInfo;
            }

        }

        // Job is always successful
        return 1;
    }
}