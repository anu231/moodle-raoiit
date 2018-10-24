<?php
/*
    This page provide functionality to add books in library //
*/
require_once('../../config.php');
require_once('books_form.php'); // All library's form came from books_form.php //
require_once('locallib.php'); // locallib page needed //
//require_once('fetch_book_info.php');
$PAGE->set_url('/blocks/library/add_books.php'); // We can set page url //
if (is_siteadmin() || is_secondary_user()){  // This page is only access siteadmin and another site admin which called as secondary user //
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Add New Book in Library'); // set title of the page //
    $PAGE->set_heading('Add New Book in Library'); // set heading 
    echo $OUTPUT->header();
    $heading="Add New Book in Library";
    echo $OUTPUT->heading($heading);
    $mform = new add_books_form(); // add books form showing in current page with the help of this object 
    if ($data = $mform->get_data()){
        $book_record = new stdClass();
        $book_record->bookid = $data->bookid; // get bookid from form 
        $book_record->name    = $data->name; // get name from form 
        $book_record->subject    = $data->subject; // get subject from form 
        $book_record->volume =  $data->volume; // get volume from form 
        $book_record->publisher    = $data->publisher; // get publisher from form 
        $book_record->author     = $data->author; // get author from form 
        $book_record->price   = $data->price; // get price from form 
        $book_record->barcode    = $data->barcode; // get barcode from form 
        $book_record->branch    = $data->branch; // get branch from form 
        $book_record->purchasedate    =  date('Y-m-d',$data->purchasedate); // get purchase date from form
        $book_record->branchissuedate    = date('Y-m-d',$data->branchissuedate); // get branchissuedate date from form
        $book_record->issued    = 0; // initial issue status is 0
        $book_record->remark    = "Available"; // initial remark is available
        $book_record->status    = $data->status; // data status came from form
        $book_record->is_scanned = 0;
        $book_record->id = $DB->insert_record('lib_bookmaster', $book_record, $returnid=true) ; // Insert Query for book insert
        echo html_writer::div('New Book has been successfully added'); // 
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
