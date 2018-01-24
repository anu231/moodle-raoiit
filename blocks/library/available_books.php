<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$branchadmin = false;
$course_id = required_param('courseid',PARAM_TEXT);
/*$course_active = get_config('library','manager_course');
$course_active = $DB->get_record('course',array('shortname'=>$course_active));
if ($course_active->id == $course_id){
    //check if user is enrolled in the course
    $context = context_course::instance($course_active->id);
    if (is_enrolled($context, $USER->id, '', true)){
        $branchadmin = true;
    } 
}*/
if (is_branch_admin()){
    $branchadmin = true;
}
$output = $PAGE->get_renderer('block_library');
$renderable = new view_available_books($branchadmin);
echo $output->header();

$PAGE->set_url('/blocks/library/available_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Available books');

echo $output->render($renderable);
//echo $OUTPUT->render($renderable);
echo $output->footer();