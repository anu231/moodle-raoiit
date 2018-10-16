<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
require_once("$CFG->libdir/raolib.php");
$PAGE->set_url('/blocks/library/approval_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Rejected Books for Approval');
global $USER, $DB,$COURSE,$OUTPUT;
echo $course_id =  $COURSE->id;
//$context = context_course::instance($CFG->branchadmin_courseid);
echo $OUTPUT->header();
if(is_siteadmin()){
    $bookid = required_param('id',PARAM_INT);
    $table = "lib_bookmaster";
    $DB->delete_records($table, array ('id'=>$bookid));
    echo html_writer::div('Book Successfully Deleted into Database');
    echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/pending_books.php');
}
else
{
    $PAGE->set_pagelayout('standard');
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to view this page</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);

}
echo $OUTPUT->footer();

?>