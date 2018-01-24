<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
$PAGE->set_url('/blocks/library/pay_fine.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Pending Fines');
global $USER, $DB;
//$context = context_course::instance($CFG->branchadmin_courseid);
echo $OUTPUT->header();
if(is_branch_admin()){
    $center_id = get_user_center($USER->id);
    $fineid = required_param('fineid',PARAM_INT);
    $fine = $DB->get_record('lib_fine_record', array('id'=>$fineid,'paid'=>0,'branch_id'=>$center_id));
    $mform = new pay_fine_form (null, array('fineid'=>$fine->id,'fine_amount'=>$fine->amount,'branch_issuer'=>$fine->branch_issuer));
    if ($data = $mform->get_data()){
        $fine_record = new stdClass();  
        $fine_record->id = $fine->id ;
        $fine_record->remark = $data->fine_remark;
        $fine_record->paid = 1;
        $DB->update_record('lib_fine_record', $fine_record);
        echo html_writer::div('Book Returned Fine successfully updated in system. Rs '.$fine->amount);
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/paid_fine.php?courseid='.$CFG->branchadmin_courseid);
    } else {
        $mform->display();
    }
}
else
{
    $PAGE->set_pagelayout('standard');
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to view this page</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);

}
echo $OUTPUT->footer();

?>