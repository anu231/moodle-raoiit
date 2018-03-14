<?php
require_once('../../config.php');
require_once('idcard_form.php');
require_once('../branchadmin/locallib.php');
require_once('../library/locallib.php');
require_once("$CFG->libdir/gdlib.php");
$PAGE->set_url('/blocks/idcard_tracker/multiple_idcard.php');

if (is_branch_admin())
{   
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Centerwise ID Cards');
    $PAGE->set_heading('Centerwise ID Cards');
    echo $OUTPUT->header();
    global $DB,$CFG,$USER,$COURSE; 
    
    $heading="Centerwise Sent ID Cards";
    echo $OUTPUT->heading($heading);
    $mform = new view_sent_idcard_form();
    if ($data = $mform->get_data()){
        // $data->branch;
        redirect(new moodle_url('view_sent_idcards.php?view_id='.$data->branch));
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