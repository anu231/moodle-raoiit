<?php

require_once('../../config.php');

// $id = required_param('id', PARAM_INT); // Course id.

// if (! $course = $DB->get_record('course', array('id'=>$id))) {
//     print_error("Course ID is incorrect");
// }

// require_course_login($course);

$PAGE->set_url('/mod/raobooklet/index.php', array('id' => $id));
$PAGE->navbar->add("booklets");
$PAGE->set_title("$course->shortname:booklets");
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading("RaoBooklets &trade; are simply the best!");

echo $OUTPUT->footer();