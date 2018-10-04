<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
require_once("$CFG->libdir/raolib.php");
$PAGE->set_url('/blocks/library/deleted_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Delete Books');
global $USER, $DB;
//$context = context_course::instance($CFG->branchadmin_courseid);
echo $OUTPUT->header();
if (is_siteadmin() || is_secondary_user()){
    $center_id = get_user_center($USER->id);
    $book_id = required_param('id',PARAM_INT);
    $bookrecords = $DB->get_record('lib_bookmaster', array('id'=>$book_id));
    $mform = new book_deleted_form (null, array('id'=>$bookrecords->id));
    if ($data = $mform->get_data()){
        $books_record = new stdClass();  
        $books_record->id = $bookrecords->id ;
        $books_record->remark = $data->remark;
        $books_record->status = -3;
        // status code -3 = book deleted from our end //
        $DB->update_record('lib_bookmaster', $books_record);
        echo html_writer::div('Book Successfully Deleted');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/delete_books.php?courseid='.$CFG->branchadmin_courseid);
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