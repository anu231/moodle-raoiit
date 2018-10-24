<?php
/*
    This page provide functionality to delete library book fine //
*/
require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_once("$CFG->libdir/raolib.php");
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/library/pending_fine_list.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Pending book Fine List');
$output = $PAGE->get_renderer('block_library'); // renderer from library block
echo $output->header();

if (is_siteadmin() || is_secondary_user()){ // this page can only access siteadmin and secondary user
    $renderable = new view_fine_list(); // get renderer fine view_fine_list from renderer.php
    echo $output->render($renderable); // Renderer output from rendering template
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
