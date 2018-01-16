<?php
require_once('../../config.php');
require_once('books_form.php');
//require_once('fetch_book_info.php');
$PAGE->set_url('/blocks/library/view_student_fine.php');
$context = context_course::instance($CFG->branchadmin_courseid);
if(is_enrolled($context, $USER->id, '', true)){
    $PAGE->set_pagelayout('standard');
    $PAGE->set_heading('View perticular student fine');
    
    echo $OUTPUT->header();
    global $USER, $DB;
  
    $branch_id = required_param('branch_id',PARAM_INT);
    $fine = $DB->get_records('lib_fine_record', array('branch_id'=>$branch_id,'paid'=>1,'is_submitted'=>0));
    //print_r($fine);
    $toatal_amount=NULL;

    foreach($fine  as $fine_record){
        $count_id = count($fine_record->id);
        $count = count($fine_record->amount);
        $id = $fine_record->id;
        $issue_id = $fine_record->issue_id;
        $student_username =$fine_record->student_username;
        $newid='';
        for ($i=0;$i<$count;$i++){
         
            $newid[]+=$fine_record->id; 
        
        $toatal_amount=$toatal_amount+$fine_record->amount; 
         }
        $paid = $fine_record->paid;
    }
      $count_id1 = count($newid);
   
      $mform = new view_submitted_ho_form (null, array('fineid'=>$newid,'fine_amount'=>$toatal_amount,'branch_id'=>$branch_id));
       if ($data = $mform->get_data()){
        for ($i=0;$i<$count_id1;$i++){
          $query_id=$newid[$i];
          $fine_record1 = new stdClass();  
          $fine_record1->id = $query_id;
         $fine_record1->branch_id = $branch_id;
          $fine_record1->is_submitted = $data->fine_status;
          $DB->update_record('lib_fine_record', $fine_record1);
          echo html_writer::div('Fine Successfully submitted in HO. RS '.$toatal_amount);
          echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/library/view_all_branch_fine.php?courseid='.$CFG->branchadmin_courseid);
        }
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