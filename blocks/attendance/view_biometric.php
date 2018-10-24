<?php
/*
    This page showing student attendance as per bio metric machin 
*/
require_once('../../config.php');
require_once('duration_form.php');
require_once('locallib.php');

//global $DB;
 
// Check for all required variables.
/*$courseid = required_param('courseid', PARAM_INT);
 
 
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_branchadmin', $courseid);
}
 
require_login($course);*/
$PAGE->set_url('/blocks/attendance/view_biometric.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Biometric Attendance');
$mform = new attendance_duration_form(); // student attendance form//
echo $OUTPUT->header();
if ($form_data=$mform->get_data()){
    $start_date = date('Y-m-d', $form_data->startdate); // start date from attendance form
    $end_date = date('Y-m-d', $form_data->enddate); // end date from attendance form
    $records = get_attendance_records($start_date,$end_date); // get attendance records as per get_attendance_records function in loacallib
    $output = $PAGE->get_renderer('block_attendance'); // renderer this block of the page 
    $renderable = new biometric_page($records); // renderer this object with biometric records
    echo $output->render($renderable);
} else {
    $mform->display();
}
echo $OUTPUT->footer();
//$simplehtml->display();
?>