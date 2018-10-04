<?php
require_once('../../config.php');
require_once('leaves_form.php');
require_once('locallib.php');
require_once('renderer.php');
global $PAGE;
$PAGE->set_url('/blocks/leaverecord/view_leave_form.php');
//$PAGE->requires->js('/blocks/leaverecord/js/leaves.js');
    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('View Apply Leaves');
    $PAGE->set_heading('View Apply Leaves');
    echo $OUTPUT->header();

    $mform = new view_leave_form();
    if ($data = $mform->get_data()){
        global $USER;
            $leave_application_form = new stdClass();
            $from_date =  date('Y-m-d',$data->from);
            $to_date =  date('Y-m-d',$data->to);
            $email = $USER->email;
            $rec = faculty_attendance($email);
            $attendance_data = faculty_attendance_detail($rec,$from_date,$to_date);
            $OUTPUT= $PAGE->get_renderer('block_leaverecord');
            $renderable = new view_attendance_master($attendance_data);
            echo $OUTPUT->render($renderable);

        } else {
        $mform->display();
    }
    echo $OUTPUT->footer();

?>