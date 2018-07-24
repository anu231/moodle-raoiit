<?php
require_once('../../config.php');
require_once('leaves_form.php');
require_once('locallib.php');
require_once('../timetable/locallib.php');
require_once('renderer.php');
require_once($CFG->libdir.'/raolib.php');
global $PAGE,$USER,$DB;
$PAGE->set_url('/blocks/leaverecord/faculty_register.php');
//$PAGE->requires->js('/blocks/leaverecord/js/leaves.js');
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('View Faculty Register');
    $PAGE->set_heading('View Faculty Register');
    echo $OUTPUT->header();
    $cur_date = date('Y-m-d');
    $next_date=date('Y-m-d', strtotime($cur_date. ' + 15 days'));

    $username = $USER->id;
    
    $output = $PAGE->get_renderer('block_leaverecord');
    $renderable = new faculty_register();
    echo $output->render($renderable);
   
    echo $OUTPUT->footer();

?>
