<?php
require_once('../../config.php');
require_once('books_form.php');
$PAGE->set_url('/blocks/library/lost_book.php');
    $PAGE->set_pagelayout('standard');
    $PAGE->set_heading('View All Lost Books');
    global $USER, $DB;
    $context = context_course::instance($CFG->branchadmin_courseid);
    if(is_enrolled($context, $USER->id, '', true)){
    $center_id = get_user_center($USER->id);
    $issue_id = required_param('issue_id',PARAM_INT);
    $book_lost = <<<SQL
    select book.id  as new_bookid, book.remark, book.bookid ,book.name, book.branch, book.issued, book.status, issue.id,issue.student_username, issue.branch_id, issue.status
    from {lib_bookmaster} as book join {lib_issue_record} as issue
    on book.id = issue.bookid
    where book.issued = 1 and book.status = 1 and issue.status = 0 and issue.id = $issue_id and issue.branch_id=?
SQL;
   $lost = $DB->get_records_sql($book_lost,array($center_id));
    foreach($lost as $lost_record){
      $id = $lost_record->new_bookid;
      $issue_lost_id = $lost_record->id;
      $student_username = $lost_record->student_username;
      $bookid = $lost_record->bookid;
      $remark = $lost_record->remark;
      $branch_id = $lost_record->branch_id;
    }
        echo $OUTPUT->header();
    $mform = new lost_books_form (null, array('lost_issue_id'=>$issue_lost_id,'lost_student_username'=>$student_username,'lost_bookid'=>$bookid));
        if ($data = $mform->get_data()){
        $lost_book_record = new stdClass();  
        $lost_book_record->id = $id ;
        $lost_book_record->remark = $data->lost_remark;
        $lost_book_record->status = $data->lost_status;
        $DB->update_record('lib_bookmaster', $lost_book_record);
        echo html_writer::div('Lost Book entry (From student only)  successfully submitted.');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/lost_update.php?courseid='.$CFG->branchadmin_courseid);
     
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
echo "<h5>You are not Authorised Person to access this page</h5>";
echo $OUTPUT->continue_button($CFG->wwwroot);

echo $OUTPUT->footer();
}

?>