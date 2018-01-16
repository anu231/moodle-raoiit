<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
//require_once('fetch_book_info.php');
$PAGE->set_url('/blocks/library/add_books.php');
if (is_siteadmin()){
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Add New Book in Library');
    $PAGE->set_heading('Add New Book in Library');
    echo $OUTPUT->header();
    $heading="Add New Book in Library";
    echo $OUTPUT->heading($heading);
    $mform = new add_books_form();
    if ($data = $mform->get_data()){
        $book_record = new stdClass();
        $book_record->bookid = $data->bookid;
        $book_record->name    = $data->name;
        $book_record->subject    = $data->subject;
        $book_record->volume =  $data->volume;
        $book_record->publisher    = $data->publisher;
        $book_record->author     = $data->author;
        $book_record->price   = $data->price;
        $book_record->barcode    = $data->barcode;
        $book_record->branch    = $data->branch;
        $book_record->purchasedate    = $data->purchasedate;
        $book_record->branchissuedate    = $data->branchissuedate;
        $book_record->issued    = 0;
        $book_record->remark    = "Available";
        $book_record->status    = $data->status;
        $book_record->id = $DB->insert_record('lib_bookmaster', $book_record, $returnid=true) ; 
        echo html_writer::div('New Book has been successfully added');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/add_books.php');
    } else {
        $mform->display();
    }
    echo $OUTPUT->footer();
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
echo "<h5>You are not Authorised Person to add or delete books</h5>";
echo "<a href='$CFG->wwwroot'>Back to Page</a>";

echo $OUTPUT->footer();
}
?>