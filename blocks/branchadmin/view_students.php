<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once("{$CFG->libdir}/formslib.php");
require_once('renderer.php');
require_once('info_form.php');
require_login();
global $DB, $USER, $CFG, $PAGE;


$PAGE->set_url('/blocks/branchadmin/view_students.php', array('id' => $COURSE->id));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Branch Students');

/*$output = $PAGE->get_renderer('block_branchadmin');
$renderable = new view_students();

echo $output->header();
echo $output->render($renderable);
echo $output->footer();*/

$mform = new branchadmin_info_form();

if ($mform->is_cancelled()){
    redirect(new moodle_url('/'));
}else if ($form_data=$mform->get_data()){
    //get the centre of logged in user 
    $username = $form_data->username;
    redirect(new moodle_url('view_student.php?userid='.$username));
}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();