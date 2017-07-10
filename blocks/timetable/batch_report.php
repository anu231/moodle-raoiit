<?php

require_once('../../config.php');

global $CFG, $DB, $PAGE;
require_login();
$PAGE->set_url('/blocks/timetable/batch_report.php');
$PAGE->set_title("Completion Report");
$PAGE->set_heading("Batch Report");
$PAGE->set_pagelayout('standard');


$output = $PAGE->get_renderer('block_timetable');
echo $output->header();
echo $output->batchreport();
echo $output->footer();
