<?php
require_once('../../config.php');
require_once('leaves_form.php');
require_once('locallib.php');
require_once('../timetable/locallib.php');
require_once('../timetable/renderer.php');
require_once('renderer.php');
require_once($CFG->libdir.'/raolib.php');
global $PAGE,$USER,$DB,$CFG;
$PAGE->set_url('/blocks/leaverecord/faculty_timetable.php');

    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('View Faculty Timetable');
    $PAGE->set_heading('View Faculty Timetable');
    echo $OUTPUT->header();
	 $cur_date = date('Y-m-d');
	 $next_date=date('Y-m-d', strtotime($cur_date. ' + 7 days'));
 	 $faculty_email = $USER->email;
	 $faculty_username = $USER->username;
   	 $faculty_empid = faculty_detail($faculty_email);
	 $faculty = get_faculty_timetable($cur_date,$next_date,$faculty_empid);
	 $output = $PAGE->get_renderer('block_timetable');
    echo $output->week(NULL,$faculty);
   
    echo $OUTPUT->footer();

?>
