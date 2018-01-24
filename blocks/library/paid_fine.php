<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_login();
$PAGE->set_url('/blocks/library/paid_fine.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Paid Fines');
global $DB, $USER, $CFG, $PAGE;
$output = $PAGE->get_renderer('block_library');
echo $output->header();
if(is_branch_admin()){
    $renderable = new view_paid_fine();
    echo $output->render($renderable);
}
else
{
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to view this page</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);
}
echo $output->footer();