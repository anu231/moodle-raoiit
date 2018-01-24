<?php
require_once('../../config.php');
require_once('locallib.php');
require_once('books_form.php');
require_once('../branchadmin/locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/library/library_issue.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Issue or Return Book to Student');
$PAGE->set_heading('Issue or Return Book to Student');
$PAGE->requires->js('/blocks/library/js/jquery.scannerdetection.min.js');
$PAGE->requires->js('/blocks/library/js/barcode.js');
$PAGE->set_pagelayout('standard');
echo $OUTPUT->header();

$active_course = get_config('library','manager_course');
$active_course = $DB->get_record('course',array('shortname'=>$active_course));
$context = context_course::instance($active_course->id);

if(is_enrolled($context, $USER->id, '', true)){
    $output = $PAGE->get_renderer('block_library');
    $heading="Issue or Return Book to Student";
    echo $OUTPUT->heading($heading);
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
            $issue_record->return_date = compute_return_date($issue_record->issue_date);
            $DB->insert_record('lib_issue_record', $issue_record) ;
            $book->issued = 1;
            $DB->update_record('lib_bookmaster',$book);
            echo html_writer::div('Book successfully issued to '.$data->student_username);
            echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/library_issue.php?courseid='.$CFG->branchadmin_courseid);

        }else if ($data->status == '1'){
            $issue_record = $DB->get_record('lib_issue_record',array('student_username'=>$data->student_username,'bookid'=>$book->id));
            $issue_record->status = 1;
            $issue_record->return_date = date('Y-m-d');
            $DB->update_record('lib_issue_record',$issue_record);
            $book->issued = 0;
            $DB->update_record('lib_bookmaster',$book);
            //echo "Book successfully Returned";
            echo html_writer::div('Book successfully Returned from '.$data->student_username);
            echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/library_issue.php?courseid='.$CFG->branchadmin_courseid);
            //check fine
            $return_days = get_config('library','issuedays');
            $fine_price = get_config('library','fine');
            $date_diff = compute_date_diff($issue_record->issue_date, $issue_record->return_date);
            if ($date_diff > $return_days){
                //compute the fine
                $fine=(($date_diff-$return_days)*$fine_price);
                //check if a fine record entry exists with this issue id
                $fine_record = $DB->get_record('lib_fine_record', array('issue_id'=>$issue_record->id));
                if ($fine_record == NULL){
                    //create one
                    $fine_record = new stdClass();
                    $fine_record->issue_id = $issue_record->id;
                    $fine_record->bookid = $book->id;
                    $fine_record->branch_issuer = $USER->username;
                    $fine_record->student_username = $issue_record->student_username;
                    $fine_record->amount = $fine;
                    $fine_record->return_date = date('Y-m-d');
                    $fine_record->book_status = $issue_record->status;
                    $fine_record->branch_id = $issue_record->branch_id;
                    $fine_record->paid = 0;
                    $fine_record->is_submitted = 0;
                    $DB->insert_record('lib_fine_record', $fine_record) ;
                } else {
                    //update the record
                    $fine_record->amount = $fine;
                    $fine_record->book_status = $issue_record->status;
                    $fine_record->return_date = date('Y-m-d');
                    $DB->update_record('lib_fine_record', $fine_record);
                }
                                
                echo "<br>";
                echo html_writer::div('Added fine with Rs '.$fine);
                echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/library_issue.php?courseid='.$CFG->branchadmin_courseid);
            }
        }
    } else {
        //render the form
        $issue_form->display();
    }
}
else
{
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to access this page</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);

}
echo $OUTPUT->footer();