<?php
require_once('../../config.php');
require_once('books_form.php');
$PAGE->set_url('/blocks/library/lost_book.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View All Lost Books');
global $USER, $DB;
echo $OUTPUT->header();
if(is_branch_admin()){
    $center_id = get_user_center($USER->id);
    $issue_id = optional_param('issue_id',null,PARAM_INT);
    $book_id = required_param('book_id', PARAM_INT);
    $lost_from = required_param('from', PARAM_TEXT);
    $mform = new lost_books_form (null, array('issue_id'=>$issue_id,'book_id'=>$book_id,'from'=>$lost_from));
    if ($data = $mform->get_data()){
        //lib_bookmaster status -1
        $book_record = $DB->get_record('lib_bookmaster', array('id'=>$data->book_id));
        $book_record->status = -1;
        $book_record->remark = 'Lost From - '.$data->from.' : '.$data->lost_remark;
        $DB->update_record('lib_bookmaster', $book_record);
        //issue_record -1
        if ($data->from == 'student'){
            $issue_record = new stdClass();
            $issue_record->status = -1;
            $issue_record->id = $data->issue_id;
            $DB->update_record('lib_issue_record', $issue_record);
            //make an entry in the fine records for student
            //check if a fine entry exists for this issue record
            $fine_record = $DB->get_record('lib_fine_record', array('issue_id'=>$data->issue_id));
            if ($fine_record == null){
                $issue_record = $DB->get_record('lib_issue_record', array('id'=>$data->issue_id));
                $fine_record = new stdClass();
                $fine_record->issue_id = $data->issue_id;
                $fine_record->bookid = $data->book_id;
                $fine_record->student_username = $issue_record->student_username;
                $fine_record->amount = $book_record->price;
                $fine_record->book_status = -1;
                $fine_record->branch_id = $book_record->branch;
                $fine_record->paid = 0;
                $DB->insert_record('lib_fine_record', $fine_record);
            } else {
                $fine_record->amount = $fine_record->amount + $book_record->price;
                $fine_record->book_status = -1;
                $fine_record->paid = 0;
                $DB->update_record('lib_fine_record', $fine_record);
            }
        }
        echo html_writer::div('Lost Book entry (From '.$data->from.')  successfully submitted.');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/lost_update.php?courseid='.$CFG->branchadmin_courseid);
    } else {
        $mform->display();
    }
} else{
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to access this page</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);
}
echo $OUTPUT->footer();
?>