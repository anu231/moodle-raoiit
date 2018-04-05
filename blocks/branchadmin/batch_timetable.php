<?php

require_once('../../config.php');
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_once("$CFG->libdir/raolib.php");
require_login();
global $DB, $USER, $CFG, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/branchadmin/batch_timetable.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('batch Timetable');

if (!is_branch_admin()){
    echo 'ACCESS DENIED';
} else {
    //get batches for the branch
    $output = $PAGE->get_renderer('block_timetable');
    $batch = required_param('id',PARAM_INT);
    echo $output->header();
    echo $output->week($batch);
    echo $output->footer();
}