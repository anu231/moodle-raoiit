<?php
/*
    This page showing timetable for faculty
*/
require_once('../../config.php');
require_once('../leaverecord/locallib.php');
require_once('locallib.php');
require_once('renderer.php');
require_once($CFG->libdir.'/raolib.php');
global $PAGE,$USER,$DB,$CFG;
$PAGE->set_url('/blocks/timetable/faculty_timetable.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title('View Faculty Timetable');
$PAGE->set_heading('View Faculty Timetable');
echo $OUTPUT->header();
$cur_date = date('Y-m-d');// current date
$next_date=date('Y-m-d', strtotime($cur_date. ' + 7 days')); // next week date
$faculty_email = $USER->email;
$faculty_username = $USER->username;
$faculty = faculty_detail($faculty_email);// get faculty detail 
$output = $PAGE->get_renderer('block_timetable');
echo $output->week(NULL,$faculty); // rendering week timetable for faculty
echo $OUTPUT->footer();
?>
