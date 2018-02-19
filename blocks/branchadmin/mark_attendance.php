<?php
require_once('../../config.php');
require_once('info_form.php');
require_once("$CFG->dirroot/blocks/library/locallib.php");
$PAGE->set_url('/blocks/branchadmin/attendance.php');

$PAGE->set_pagelayout('standard');
$PAGE->set_title('Get Attendance');
$PAGE->set_heading('Attendance');
echo $OUTPUT->header();
$heading="Set Attendance";
echo $OUTPUT->heading($heading);

if (is_branch_admin()){    
    $PAGE->requires->js('/blocks/branchadmin/js/attendance.js');
    $mform = new attendance_form();
    if ($data = $mform->get_data()){
        $result = new stdClass();
        $result->date         = $data->date;
        $result->batch        = $data->batch;
        $result->schedule_id = $_POST['schedule_id'];
        //$result->topic_id     = $data->topic_id;
        $result->roll_numbers = $data->roll_numbers;
        $result->sms_status   = 0;
        $result->id = $DB->insert_record('attendance', $result, $returnid=true) ; 
        //send sms to these students asn their parents
        $stud_data = bulk_fetch_numbers_for_students(explode(',', $data->roll_numbers));
        $sms_task = new block_branchadmin_smsnotification();
        $sms_task->set_custom_data(array(
            'numbers'=>$stud_data,
            'message'=>'Absent SMS',
            'sender'=>$USER->id
        ));
        //$sms_task->execute();
        if( !$taskid = \core\task\manager::queue_adhoc_task($sms_task) ) {
            //failed
        }else{
            //success
        }
        echo html_writer::div('Absent Information has been successfully added. SMS will be sent to the specified students and parents');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/branchadmin/mark_attendance.php');
    } else {
        $mform->display();
    }
}
else
{
    GLOBAL $USER;
    $firstname=$USER->firstname;
    $lastname= $USER->lastname;
    $fullname=$firstname." ".$lastname;
    echo "<h5>Dear, $fullname </h5>";
    echo "<br>";
    echo "<h5>You are not Authorised Person to mark attendance</h5>";
    echo "<a href='$CFG->wwwroot'>Back to Page</a>";
}
echo $OUTPUT->footer();
?>