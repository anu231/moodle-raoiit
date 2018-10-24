<?php
/*
    This page showing all books in Rao Centers. This page is only access to moodle siteadmin //
*/
require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php'); // Require locallib. Locallib contains local function which is used for library. //
require_once("$CFG->libdir/raolib.php");
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/library/view_books_siteadmin.php');
//$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View all Books'); // Page heading //
$output = $PAGE->get_renderer('block_library'); //Get renderer from Library block//
echo $output->header();
if (is_siteadmin() || is_secondary_user()){ // This page is only access siteadmin and another site admin which called as secondary user //
    $renderable = new view_all_books_siteadmin(); // get renderer all books from renderer page with the help of view_all_books_siteadmin class
    echo $output->render($renderable);
}else {
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to access this page</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);
}
echo $output->footer();