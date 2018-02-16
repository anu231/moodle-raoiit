<?php
require_once('../../config.php');
require_once('info_form.php');
$PAGE->set_url('/blocks/branchadmin/attendance.php');
if (is_siteadmin()){
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Get Attendance');
    $PAGE->set_heading('Attendance');
    $PAGE->requires->js('/blocks/branchadmin/js/batch.js');
    echo $OUTPUT->header();
    $heading="Get Attendance";
    echo $OUTPUT->heading($heading);
    $mform = new attendane_form();
    if ($data = $mform->get_data()){
        $result = new stdClass();
        $result->date         = $data->date;
        $result->batch        = $data->batch;
        $result->scheduled_id = $data->scheduled_id;
        $result->tpoic_id     = $data->tpoic_id;
        $result->roll_numbers = $data->roll_numbers;
        $result->sms_status   = $data->sms_status;
      
        $result->id = $DB->insert_record('attendance', $result, $returnid=true) ; 
        echo html_writer::div('New Information has been successfully added');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/branchadmin/attendance.php');
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
    echo "<h5>You are not Authorised Person to add or delete books</h5>";
    echo "<a href='$CFG->wwwroot'>Back to Page</a>";
}
echo $OUTPUT->footer();
?>