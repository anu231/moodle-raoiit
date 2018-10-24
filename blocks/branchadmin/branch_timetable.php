<?php

require_once('../../config.php');
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_once("$CFG->libdir/raolib.php");
require_login();
global $DB, $USER, $CFG, $PAGE, $OUTPUT;

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/blocks/branchadmin/branch_timetable.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Branch Timetable');

if (!is_branch_admin()){
    echo 'ACCESS DENIED';
} else {
    //get batches for the branch
    $output = $PAGE->get_renderer('block_branchadmin'); // renderer branchadmin block 
    $renderable = new branch_batches('batch_timetable.php?id='); // 
    echo $output->header();
    echo '<h3>Select Batch</h3>';
    echo $output->render($renderable); 
    echo $output->footer();
}