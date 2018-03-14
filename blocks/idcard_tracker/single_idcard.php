<?php
require_once('../../config.php');
require_once('idcard_form.php');
require_once("$CFG->dirroot/blocks/library/locallib.php");
$PAGE->set_url('/blocks/idcard_tracker/add_idcard.php');

if (is_branch_admin())
{ 
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Add New ID Card in Edumate');
    $PAGE->set_heading('Add New ID Card in Edumate');
    echo $OUTPUT->header();
    global $DB,$CFG,$USER,$COURSE; 
    
    $heading="Add New ID Card in Edumate";
    echo $OUTPUT->heading($heading);
    $mform = new single_idcard_form();
    if ($data = $mform->get_data()){
        $data->student_username;
        $student = $DB->get_record('user',array('username'=>$data->student_username));   
       // profile_load_data($student);
        redirect(new moodle_url('view_single_idcard.php?single_id='.$data->student_username));
      
    } else {
        $mform->display();
    }
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
    echo "<h5>You are not Authorised Person to add id card info</h5>";
    echo "<a href='$CFG->wwwroot'>Back to Page</a>";
}
echo $OUTPUT->footer();
?>