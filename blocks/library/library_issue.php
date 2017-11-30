<?php
require_once('../../config.php');
require_once('locallib.php');
require_once('books_form.php');
require_once('../branchadmin/locallib.php');

require_login();
global $DB, $USER, $CFG, $PAGE;

$PAGE->set_url('/blocks/library/library_issue.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Issue a book to student');
$PAGE->requires->js('/blocks/library/js/jquery.scannerdetection.min.js');
$PAGE->requires->js('/blocks/library/js/barcode.js');
$output = $PAGE->get_renderer('block_library');
echo $output->header();

//render the form
$issue_form = new issue_book_form();
if ($data = $issue_form->get_data()){
    $book = $DB->get_record('lib_bookmaster', array("status"=>1,"barcode"=>$data->book_barcode));
    if( $data->status=='0'){
        $new_issue_bookstatus=1;
        $issue_record = new stdClass();  
        $issue_record->bookid = $book->id;
        $issue_record->branch_issuer = $USER->username;
        $issue_record->student_username = $data->student_username;
        $issue_record->issue_date = date('Y-m-d');
        $issue_record->branch_id = get_user_center();
        $issue_record->status = 0;
        $DB->insert_record('lib_issue_record', $issue_record) ;
        $book->issued = 1;
        $DB->update_record('lib_bookmaster',$book);
    }else if ($data->status == '1'){
        //load the issue record
        $issue_record = $DB->get_record('lib_issue_record',array('student_username'=>$data->student_username,'bookid'=>$book->id));
        $issue_record->status = 1;
        $issue_record->return_date = date('Y-m-d');
        $DB->update_record('lib_issue_record',$issue_record);
        $book->issued = 0;
        $DB->update_record('lib_bookmaster',$book);
        //check fine
        global $return_days;
        if (compute_date_diff($issue_record->issue_date, $issue_record->return_date)>$return_days){
            //fine record entry
        }
    }
} else {
    //render the form
    $issue_form->display();
}
echo $output->footer();
