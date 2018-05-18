<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
require_once("$CFG->libdir/raolib.php");
$PAGE->set_url('/blocks/library/delete_fine.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Delete Fines');
global $USER, $DB;
//$context = context_course::instance($CFG->branchadmin_courseid);
echo $OUTPUT->header();
if(is_siteadmin() || is_secondary_user()){
    //$center_id = get_user_center($USER->id);
    $fineid = required_param('fineid',PARAM_INT);
    //$book_id = required_param('book_id',PARAM_RAW);
    $fine = $DB->get_record('lib_fine_record', array('id'=>$fineid,'paid'=>0));
    $mform = new pay_delete_form (null, array('fineid'=>$fine->id,'fine_amount'=>$fine->amount,'branch_issuer'=>$fine->branch_issuer,'return_date'=>$fine->return_date,'student_username'=>$fine->student_username,'branch'=>$fine->branch_issuer,'branch_id'=>$fine->branch_id));
    if ($data = $mform->get_data()){
        $fine_record = new stdClass();  
        $fine_record->id = $fine->id ;
        //$fine_record->remark = $data->fine_remark;
        //$fine_record->amount = $data->fine_amount;
        //$fine_record->return_date = $data->returndate;
        //$fine_record->paid = $data->pay_or_not;
        $fine_record->paid = -1; // fine record status become -1//
        //$fine_record->is_submitted = $data->is_submitted;
        $DB->update_record('lib_fine_record', $fine_record);
        echo html_writer::div('Successfully updated in system');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/course/view.php?id=15');
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