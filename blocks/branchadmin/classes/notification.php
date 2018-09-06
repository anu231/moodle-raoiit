<?php

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');

class block_branchadmin_notification extends \core\task\adhoc_task {
    public function execute(){
        global $DB;
        $data = $this->get_custom_data();
        $notification_addresses = notification_filter(Array('courses'=>$data->courses));
        $email_error_log = Array();
        $sms_error_log = Array();
        if ($data->email == '1'){
            //send email
            foreach($notification_addresses as $uid => $stud){
                if (sendEmail($data->from, $stud['email'][0],
                array_slice($stud['email'],1),$data->subject,$data->content) != 202){
                    array_push($email_error_log, $stud['username']);
                }
            }
            //all emails send email error log to user who issued this request :)
        }
        if ($data->sms == '1'){
            //send sms
            foreach($notification_addresses as $uid => $stud){
                $mobs = implode(',',$stud['mobile']);
                if (!sendSMS($mobs, $data->content)){
                    array_push($sms_error_log, $stud['username']);
                }
            }
        }
        $email_error_log = implode(',',$email_error_log);
        $sms_error_log = implode(',', $sms_error_log);
        $user = $DB->get_record('user',array('id'=>$this->get_userid()));
        $subject = "Notification Report";
        $msg = <<<MSG
        Dear $user->username,
        Your notification has been sent via email/sms as selected by you.
        Email messages encountered error for the following students -
        $email_error_log
        
        SMS messages encountered error for the following users - 
        $sms_error_log

        Regards,
        Edumate Team
MSG;
        email_to_user($user,'edumate@raoiit.com', $subject,$msg);
    }
}