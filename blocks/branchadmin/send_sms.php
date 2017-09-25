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

$batch = optional_param('batch', null, PARAM_INT);
echo $OUTPUT->header();
if ($batch == null){
    //display the batch form
    $batch_select_form = new branchadmin_batch_select_form();
    if ($batch_select_form->is_cancelled()){
        redirect(new moodle_url($CFG->wwwroot));
    } else if($form_data=$batch_select_form->get_data()){
        $batch = $form_data->batch;
        redirect(new moodle_url($CFG->wwwroot.'/blocks/branchadmin/send_sms.php?batch='.$batch));
    } else {
        //display the batch select form
        $batch_select_form->display();
    }
} else {
    //initiate the student select form
    $student_form = new branchadmin_batch_students_form(null, array('batch'=>$batch));    
    if ($student_form->is_cancelled()){
        //display the main send_sms.php
        redirect(new moodle_url($CFG->wwwroot.'/blocks/branchadmin/send_sms.php'));
    } else if ($sms_form_data = $student_form->get_data()){
        //display the date_add
        $students = null;
        if (property_exists($sms_form_data,'sendtoall')){
            //send to all the students of the batch
            $students_temp = convert_std_to_array(get_students_by_batch($sms_form_data->batch));
            $students = Array();
            foreach($students_temp as $stud){
                $students[] = explode(' ',$stud)[0];
            }
        }else {
            $students = $sms_form_data->students;
        }
        $stud_data = bulk_fetch_numbers_for_students($students);
        $sms_task = new block_branchadmin_smsnotification();
        $sms_task->set_custom_data(array(
            'numbers'=>$stud_data,
            'message'=>$sms_form_data->sms,
            'sender'=>$USER->id
        ));
        //$sms_task->execute();
        if( !$taskid = \core\task\manager::queue_adhoc_task($sms_task) ) {
            //failed
        }else{
            //success
        }
    } else{
        //display student s4elect form
        $student_form->display();
    }
}


//$student_form = new branchadmin_batch_students_form();

/*
//check the sms form 1st
if ($student_form->is_cancelled()){
    //display the main send_sms.php
    redirect(new moodle_url($CFG->wwwroot.'/blocks/branchadmin/send_sms.php'));
} else if ($sms_form_data = $student_form->get_data()){
    echo html_writer::tag('h1','Received Data');
} else{
        if ($batch_select_form->is_cancelled()){
            redirect(new moodle_url($CFG->wwwroot));
        } else if($form_data=$batch_select_form->get_data()){
        //data has been validated 
        //process the data
        $batch = $form_data->batches;
        //display the students in the batch
        echo html_writer::tag('h4','Selected Batch :'.get_batch_name($batch));
        $student_form = new branchadmin_batch_students_form(null, array('batch'=>$batch));
        $student_form->display();

        /*fetch_numbers_for_userid(1475);
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
    /*} else{
        $batch_select_form->display();
    }
}
*/


echo $OUTPUT->footer();
