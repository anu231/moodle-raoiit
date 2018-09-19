<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
require_once("$CFG->libdir/raolib.php");
$PAGE->set_url('/blocks/library/approval_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Pending Books for Approval');
global $USER, $DB;
//$context = context_course::instance($CFG->branchadmin_courseid);
echo $OUTPUT->header();
if(is_branch_admin()){
    $bookid = required_param('id',PARAM_INT);
    $record = $DB->get_record('lib_bookmaster', array('id'=>$bookid));
    $mform = new approval_books_form (null, array('id'=>$record->id,'branch'=>$record->branch,'subject'=>$record->subject,'name'=>$record->name,'volume'=>$record->volume,'price'=>$record->price,'publisher'=>$record->publisher,'author'=>$record->author));
    if ($data = $mform->get_data()){
        $book_record = new stdClass();  
        $book_record->id = $record->id ;
        $book_record->bookid = $data->bookid;
        $book_record->status = 1;
        $DB->update_record('lib_bookmaster', $book_record);
        echo html_writer::div('Book Successfully Added into Database');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/pending_books.php');
    } else {
        $mform->display();
    }
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