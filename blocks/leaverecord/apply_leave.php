<?php
require_once('../../config.php');
require_once('leaves_form.php');
require_once('locallib.php');

$PAGE->set_url('/blocks/leaverecord/apply_leaves.php');
$PAGE->requires->js('/blocks/leaverecord/js/leaves.js');


    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Apply Leaves for faculty');
    $PAGE->set_heading('Apply Leaves for faculty');
    echo $OUTPUT->header();
    $heading="Apply Leaves for faculty";
    echo $OUTPUT->heading($heading);
    $mform = new leave_application_form();
    if ($data = $mform->get_data()){
            $leave_application_form = new stdClass();
            $full_day = date('Y-m-d',$data->date); 
            $leave_status = $data->leave_status;
            $leave_from =  date('Y-m-d',$data->leave_from);
            $leave_to =  date('Y-m-d',$data->leave_to);
            $leave_reason =  $data->leave_reason;
            if($leave_status==0){ // full day leave
                apply_leave($full_day,$leave_reason,$leave_status);
            }
            if($leave_status==3){ // 3 = MD
                md_leave($leave_from,$leave_to,$leave_reason);
            }
            //var_dump(http_response_code());
        echo html_writer::div('Leave has been successfully added');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/leaverecord/apply_leaves.php?blockid=2049');
    } else {
        $mform->display();
    }
    echo $OUTPUT->footer();

?>