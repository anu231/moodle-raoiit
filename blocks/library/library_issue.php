<?php

require_once('../../config.php');
//require_once('renderer.php');
require_once('locallib.php');
require_once('books_form.php');
require_login();
global $DB, $USER, $CFG, $PAGE;

$PAGE->set_url('/blocks/library/library_issue.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Issue a book to student');

$output = $PAGE->get_renderer('block_library');
echo $output->header();
$PAGE->requires->js('/blocks/library/js/jquery.scannerdetection.min.js');
$PAGE->requires->js('/blocks/library/js/barcode.js');
//render the form
$issue_form = new issue_book_form();
if ($data = $issue_form->get_data()){
    
} else {
    //render the form
    $issue_form->display();
}
//$renderable = new view_available_books();
echo $output->footer();
