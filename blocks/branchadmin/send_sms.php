<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once('sms_form.php');
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE, $OUTPUT;

$PAGE->set_url('/blocks/branchadmin/send_sms.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Send SMS to students');

$mform = new branchadmin_sms_form();

if ($mform->is_cancelled()){
    redirect(new moodle_url('/'));
} else if($form_data=$mform->get_data()){
    //data has been validated 
    //process the data
    $roll_nos = $form_data->usernames;
    $batches = $form_data->batches;
    fetch_numbers_for_userid(1475);
    if (count($batches)!=0){
        //its a batch based message
        //fetch the roll numebrs belonging to this batch
        $roll_nos = get_usernames_by_batch($batches);
    }
    
    //create the adhoc task
    $sms_task = new block_branchadmin_smsnotification();
    $sms_task->set_custom_data(array(
        'usernames'=>$roll_nos,
        'message'=>$form_data->message
    ));
    $sms_task->execute();
    /*if( !$taskid = \core\task\manager::queue_adhoc_task($task) ) {
        //failed
    }else{
        //success
    }*/
    //got the roll numbers. Now send sms
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
