<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once('sms_form.php');
<<<<<<< HEAD

require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
=======
>>>>>>> 4e821aedb13279117d290e66d42e2a0f394357f0
require_login();
global $DB, $USER, $CFG, $PAGE, $OUTPUT;

$PAGE->set_url('/blocks/branchadmin/send_sms.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Send SMS to students');

$mform = new branchadmin_sms_form();

<<<<<<< HEAD
$branchadmin_sms_form = new branchadmin_sms_form();


echo $output->header();
     $branchadmin_sms_form->display();
echo $output->footer();
=======
if ($mform->is_cancelled()){
    redirect(new moodle_url('/'));
} else if($form_data=$mform->get_data()){
    //validate data
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
>>>>>>> 4e821aedb13279117d290e66d42e2a0f394357f0
