<?php

require_once('../../config.php');

$paperid = required_param('pid', PARAM_INT);
$courseid = required_param('cid', PARAM_INT);

$coursecontext = context_course::instance($courseid);
$usercontext = context_user::instance($USER->id);

require_login();

// Page setup
$PAGE->set_url('/blocks/paper/view.php', array('id' => $paperid));
$PAGE->set_title(format_string("Paper"));
$PAGE->set_heading(format_string("Paper"));
$PAGE->set_pagelayout('standard');

$output = $PAGE->get_renderer('block_paper');

echo $output->header();
echo $output->paper($paperid);
echo $output->footer();
