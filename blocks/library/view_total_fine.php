<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$context = context_course::instance($CFG->branchadmin_courseid);
if(is_enrolled($context, $USER->id, '', true)){
$PAGE->set_url('/blocks/library/issued_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Issued books');
$output = $PAGE->get_renderer('block_library');
$renderable = new view_all_booksfine();

echo $output->header();


echo $output->render($renderable);
echo $output->footer();
}
else
{
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();
GLOBAL $USER;
$firstname=$USER->firstname;
$lastname= $USER->lastname;
 $fullname=$firstname." ".$lastname;
echo "<h5>Dear, $fullname </h5>";
echo "<br>";
echo "<h5>You are not Authorised Person to view this page</h5>";
echo $OUTPUT->continue_button($CFG->wwwroot);

echo $OUTPUT->footer();
}