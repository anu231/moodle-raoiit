<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
$PAGE->set_url('/blocks/library/add_barcode.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Add Barcode in Library');
$PAGE->set_heading('Add Barcode in Library');
$PAGE->requires->js('/blocks/library/js/jquery.scannerdetection.min.js');
$PAGE->requires->js('/blocks/library/js/lib_barcode_add.js');
echo $OUTPUT->header();
$heading="Add Barcodes to Books in Library";
echo $OUTPUT->heading($heading);
if (is_branch_admin()){
    $mform = new add_barcode_form();
    if ($data = $mform->get_data()){
        $addBook_record = new stdClass();
        $addBook_record->id      =    $data->book;
        $addBook_record->barcode =    $data->book_barcode;
        $addBook_record->is_scanned = 1;
        $DB->update_record('lib_bookmaster',$addBook_record);
        echo html_writer::div("Barcode $data->book_barcode successfully assign to this book");
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/add_barcode.php');
    } else {
        $mform->display();
    }
}
else
{
    GLOBAL $USER;
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to add or delete books</h5>";
    echo "<a href='$CFG->wwwroot'>Back to Page</a>";
}
echo $OUTPUT->footer();
?>