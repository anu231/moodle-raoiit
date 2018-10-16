<?php
require_once('../../config.php');
require_once('books_form.php');
require_once('locallib.php');
require_once("$CFG->libdir/raolib.php");
$PAGE->set_url('/blocks/library/approval_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Pending Books for Approval');
global $USER, $DB;
//$context = context_course::instance($CFG->branchadmin_courseid);
echo $OUTPUT->header();
if(is_siteadmin()){
    $bookid = required_param('id',PARAM_INT);
    $record = $DB->get_record('lib_bookmaster', array('id'=>$bookid));
    $subject_code = strtoupper(substr($record->subject, 0, 1));
    $new_code = $record->branch.$subject_code;
    if(strlen($new_code)==2){
        $new_code = "0".$record->branch.$subject_code;
    }
    else{
         $new_code = $record->branch.$subject_code;
    }
    $new = $DB->get_records_sql("SELECT * FROM {lib_bookmaster} WHERE bookid LIKE '$new_code%' ORDER BY bookid DESC LIMIT 0,1");
    foreach ($new as $value)
    {
        $new_id = $value->id;
    }
    $new_code = $new[$new_id]->bookid;
    for ($n=0; $n<1; $n++) {
         ++$new_code . PHP_EOL;
    }
    $mform = new approval_books_form (null, array('id'=>$record->id,'branch'=>$new_code,'subject'=>$record->subject,'name'=>$record->name,'volume'=>$record->volume,'price'=>$record->price,'publisher'=>$record->publisher,'author'=>$record->author));
    if ($data = $mform->get_data()){
        $book_record = new stdClass();  
        $book_record->id = $record->id ;
        $book_record->bookid = $data->bookid;
        $book_record->status = 0;
        $DB->update_record('lib_bookmaster', $book_record);
        echo html_writer::div('Book Successfully Added into Database');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/pending_books.php');
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