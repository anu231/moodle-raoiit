<?php
require_once('../../config.php');
require_once('books_form.php');
//require_once('fetch_book_info.php');
$PAGE->set_url('/blocks/library/add_books.php');
if (is_siteadmin())
{
    $PAGE->set_pagelayout('standard');
    $PAGE->set_heading('View All Books');
    echo $OUTPUT->header();
    $mform = new add_books_form();
    if ($data = $mform->get_data()){
        $table='lib_fine_record';
        $book_record = new stdClass();        
        $book_record->book_name = $data->book_name;
        $book_record->volume    = $data->volume;
        $book_record->publisher = $data->publisher;
        $book_record->author    = $data->author;
        $book_record->price     = $data->price;
        $book_record->barcode   = $data->barcode;
        $book_record->branch    = $data->branch;
        $book_record->status    = $data->status;
        $book_record->id = $DB->insert_record_raw($table, $book_record, $returnid=true, $bulk=false) ; 
        /*
        $update = "UPDATE {lib_fine_record} SET fine_status='$fine_status' WHERE barcode='$barcode'";
        $DB->execute($update);
        echo "Fine status updated successfully";
        */

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