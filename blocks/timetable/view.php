<?php

require_once('../../config.php');

global $CFG, $DB, $PAGE;
require_login();
$PAGE->set_url('/blocks/timetable/view.php');
$PAGE->set_title("Week Timetable");
$PAGE->set_heading("Week Timetable");
$PAGE->set_pagelayout('standard');
$PAGE->requires->js_call_amd('block_timetable/time', 'initialise', $params);
$output = $PAGE->get_renderer('block_timetable');

echo $output->header();
echo $output->week();
echo $output->footer();
