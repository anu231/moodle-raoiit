<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once("{$CFG->libdir}/formslib.php");
require_once('renderer.php');
require_once('info_form.php');
require_login();
global $DB, $USER, $CFG, $PAGE;


$PAGE->set_url('/blocks/branchadmin/view_students.php', array('id' => $COURSE->id)); // course id //
$PAGE->set_pagelayout('standard'); // Set standard Layout
$PAGE->set_heading('View Branch Students');

$output = $PAGE->get_renderer('block_branchadmin');
$renderable = new branch_batches('student_batchwise.php?id=');

echo $output->header();
echo $output->render($renderable); // get renderable  branch_batches in renderer.php //
$mform = new branchadmin_info_form(); // get branchadmin_info_form from info_form.php //

if ($mform->is_cancelled()){
    redirect(new moodle_url('/'));
}else if ($form_data=$mform->get_data()){
    //get the centre of logged in user 
    $username = $form_data->username;
    redirect(new moodle_url('view_student.php?userid='.$username));
}

$mform->display();
echo $output->footer();