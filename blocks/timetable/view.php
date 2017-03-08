<?php

require_once('../../config.php');

require_login();
$PAGE->set_url('/blocks/paper/view.php');
$PAGE->set_title(format_string("Paper"));
$PAGE->set_heading(format_string("Paper"));
$PAGE->set_pagelayout('standard');

$output = $PAGE->get_renderer('block_timetable');

echo $output->header();
echo $output->week();
echo $output->footer();
