<?php
 
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
$mform = new attendance_duration_form();
echo $OUTPUT->header();
if ($form_data=$mform->get_data()){
    $start_date = date('Y-m-d', $form_data->startdate);
    $end_date = date('Y-m-d', $form_data->enddate);
    $records = get_attendance_records($start_date,$end_date);
    $output = $PAGE->get_renderer('block_attendance');
    $renderable = new biometric_page($records);
    echo $output->render($renderable);
} else {
    $mform->display();
}
echo $OUTPUT->footer();
//$simplehtml->display();
?>