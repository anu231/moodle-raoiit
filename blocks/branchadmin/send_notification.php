<?php

require_once('../../config.php');
require_once("{$CFG->libdir}/formslib.php");
require_once('notification_forms.php');
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE, $OUTPUT;

$PAGE->set_url('/blocks/branchadmin/send_notification.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Send Email/SMS to students');
echo $OUTPUT->header();
if ($USER->username == 'admin' || $USER->username == 'abhishek.pawar' || $USER->username == 'saurabhadmin'){
    $notification = new notification_form();
    if ($notification->is_cancelled()){
        redirect(new moodle_url($CFG->wwwroot));
    } else if ($form_data=$notification->get_data()){
        $notification_task = new block_branchadmin_notification();
        $notification_task->set_custom_data(
            array(
                'email'=>$form_data->email,
                'sms'=>$form_data->sms,
                'subject'=>$form_data->subject,
                'content'=>$form_data->notification,
                'from'=>$form_data->from,
                'courses'=>$form_data->course
            )
        );
        $notification_task->set_userid($USER->id);
        if( !$taskid = \core\task\manager::queue_adhoc_task($notification_task) ) {
            //failed
            echo 'FAILED';
        }else{
            //success
            echo "SUCCESS";
        }
    } else {
        $notification->display();
    }
} else {
    echo 'Access Denied';
}

echo $OUTPUT->footer();