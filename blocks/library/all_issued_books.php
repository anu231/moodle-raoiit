<?php
/*
    This page provide functionality to showing all issued books in centers to siteadmin and secondary user //
*/
require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_once("$CFG->libdir/raolib.php");
require_login();
$PAGE->set_url('/blocks/library/all_issued_books.php');
//$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Issue Books');
global $DB, $USER, $CFG, $PAGE;
$output = $PAGE->get_renderer('block_library');
echo $output->header();
if (is_siteadmin() || is_secondary_user()){ // this page can only access siteadmin and secondary user
    $renderable = new view_all_issued_books(); // get renderer  view_all_issued_books from renderer.php
    echo $output->render($renderable); // Renderer output from rendering template
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