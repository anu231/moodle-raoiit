<?php

require_once('../../config.php');

global $DB, $COURSE, $USER;

$id = required_param('id', PARAM_INT);
// $coursecontext = context_course::instance($COURSE->id);
// $usercontext = context_user::instance($USER->id);

require_login();
// Get the instance
if ($id) {
    $cm = get_coursemodule_from_id('paper', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $paper = $DB->get_record('paper', array('id'=>$cm->instance), '*', MUST_EXIST);
} else {
    error('Your must specify a course_module ID or an instance ID');
}


// Page setup
$PAGE->set_url('/mod/paper/view.php', array('id' => $id));
$PAGE->set_title(format_string("Paper"));
$PAGE->set_heading(format_string("Paper"));
$PAGE->set_pagelayout('standard');

$output = $PAGE->get_renderer('mod_paper');

echo $output->header();
echo $output->paper($paper);
echo $output->footer();
