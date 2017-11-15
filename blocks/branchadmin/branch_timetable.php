<?php

require_once('../../config.php');
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
//require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/branchadmin/branch_timetable.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Branch Timetable');

