<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once('sms_form.php');
require_login();
global $DB, $USER, $CFG, $PAGE, $OUTPUT;

$PAGE->set_url('/blocks/branchadmin/view_student.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Send SMS to students');

$mform = new branchadmin_sms_form();

if ($mform->is_cancelled()){
    redirect(new moodle_url('/'));
} else if($form_data=$mform->get_data()){
    //validate data
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();