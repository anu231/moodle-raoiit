<?php

require_once('../../config.php');

$courseid = required_param('cid', PARAM_INT);

$coursecontext = context_course::instance($courseid);
$usercontext = context_user::instance($USER->id);

require_login();


// Page setup
$PAGE->set_url('/blocks/paper/view.php');
$PAGE->set_title(format_string("Paper"));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(format_string("Paper"));

$output = $PAGE->get_renderer('block_paper');

echo $output->header();
if( $courseid == 1){
    echo $output->all_paper_list();
} else {
    echo $output->course_paper_list($courseid);
}
echo $output->footer();
