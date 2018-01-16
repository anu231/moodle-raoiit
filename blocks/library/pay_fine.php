<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
$PAGE->set_url('/blocks/library/pay_fine.php');
    $PAGE->set_pagelayout('standard');
    $PAGE->set_heading('View fine Books');
    global $USER, $DB;
    $context = context_course::instance($CFG->branchadmin_courseid);
    if(is_enrolled($context, $USER->id, '', true)){
    $center_id = get_user_center($USER->id);
    $fineid = required_param('fineid',PARAM_INT);
    $fine = $DB->get_records('lib_fine_record', array('id'=>$fineid,'paid'=>0));
    foreach($fine as $fine_record){
      $id = $fine_record->id;
      $issue_id = $fine_record->issue_id;
      $student_username = $fine_record->student_username;
      $amount = $fine_record->amount;
      $paid = $fine_record->paid;
      $branch_issuer = $fine_record->branch_issuer; 
    }
    
    echo $OUTPUT->header();
    $mform = new pay_fine_form (null, array('fineid'=>$id,'fine_amount'=>$amount,'branch_issuer'=>$branch_issuer));
        if ($data = $mform->get_data()){
        $fine_record = new stdClass();  
        $fine_record->id = $id ;
        $fine_record->remark = $data->fine_remark;
        $fine_record->paid = $data->fine_status;
        $DB->update_record('lib_fine_record', $fine_record);
        echo html_writer::div('Book Returned Fine successfully updated in system. RS '.$amount);
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/paid_fine.php?courseid='.$CFG->branchadmin_courseid);
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
echo "<h5>You are not Authorised Person to view this page</h5>";
echo $OUTPUT->continue_button($CFG->wwwroot);

echo $OUTPUT->footer();
}

?>