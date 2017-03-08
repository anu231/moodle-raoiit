<?php
// defined('MOODLE_INTERNAL') || die();
global $COURSE;
require_once('../../config.php');
require_once('paper_form.php');
require_once('locallib.php');

// Get the course from the db
$courseid = required_param('courseid', PARAM_INT);

// CHECKS

require_login();

if (!has_capability('block/paper:assignpaper',context_course::instance($courseid))){
    redirect(new moodle_url('/'));
}

// Check if courseid is valid
if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_paper', $courseid);
}

// CHECKS END


// PAGE SETUP
$PAGE->set_url('/blocks/paper/view.php', array('id' => 2));
$PAGE->set_title(format_string("Paper: "));
$PAGE->set_heading(format_string("Paper: "));


// Page rendering
$paperform = new paper_form(new moodle_url("/blocks/paper/add_paper.php?courseid=$courseid"));
if($paperform->is_cancelled()) {
    // Cancelled forms redirect to the course main page.
    $courseurl = new moodle_url('/my', array('id' => $id));
    redirect($courseurl);
} else if ($paperform->is_submitted()) {
    // Save data
    paper_add_paper($paperform->get_data(), $courseid);
    $courseurl = new moodle_url('/my'); // TODO redirect to view.php
    redirect($courseurl);

} else {
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Assign paper to <u>'.$course->fullname.'</u>');
    $paperform->display();
    echo $OUTPUT->footer();
}


