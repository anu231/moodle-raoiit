<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/library/pending_fine.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Available books');
$output = $PAGE->get_renderer('block_library');
echo $output->header();

if (is_branch_admin()){
    $renderable = new view_fine_books();
    echo $output->render($renderable);
    echo $output->footer();
} else {
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to access this page</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);
}

echo $OUTPUT->footer();
