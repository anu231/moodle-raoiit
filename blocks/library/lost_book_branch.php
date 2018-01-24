<?php
require_once('../../config.php');
require_once('books_form.php');
global $USER, $DB,$PAGE,$OUTPUT;
$PAGE->set_url('/blocks/library/lost_book_branch.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Lost Book Entry');
echo $OUTPUT->header();
if(is_branch_admin()){
    $center_id = get_user_center($USER->id);
    $book_id = required_param('book_id',PARAM_INT);
    $book_record = $DB->get_record('lib_bookmaster',array('id'=>$book_id,'status'=>1));
    if ($book_record->branch == $center_id)
    {
    
        $heading="Book Lost from Branch";
        echo $OUTPUT->heading($heading);
        $mform = new lost_books_branch_form (null, array('book_id'=>$book_id,'bookid'=>$book_record->bookid,'book_name'=>$book_record->name));
        if ($data = $mform->get_data()){
            $lost_book_record = new stdClass();  
            $lost_book_record->id = $book_id ;
            $lost_book_record->remark = $data->lost_remark;
            $lost_book_record->status = $data->lost_status;
            $DB->update_record('lib_bookmaster', $lost_book_record);
            echo html_writer::div('Lost Book entry (From Branch only) successfully submitted.');
            echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/lost_update.php?courseid='.$CFG->branchadmin_courseid);
        } else {
            $mform->display();
        }
    }
} else {
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>This book does not belong to your branch</h5>";
    echo $OUTPUT->continue_button($CFG->wwwroot);
}
echo $OUTPUT->footer();
?>