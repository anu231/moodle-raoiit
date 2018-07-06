<?php
require_once('../../config.php');
require_once('leaves_form.php');
require_once('locallib.php');

$PAGE->set_url('/blocks/leaverecord/apply_leaves.php');
$PAGE->requires->js('/blocks/leaverecord/js/leaves.js');


    $PAGE->set_pagelayout('standard');
    $PAGE->set_title('Apply OD for faculty');
    $PAGE->set_heading('Apply OD for faculty');
    echo $OUTPUT->header();
    $heading="Apply OD for faculty";
    echo $OUTPUT->heading($heading);
    $mform = new od_application_form();
    if ($data = $mform->get_data()){
        $od_application_form = new stdClass();
             $date =  date('Y-m-d',$data->od_date);
             $reason =  $data->od_reason;
             $start_time_hour =  $data->od_start_time_in_hour;
             $start_time_min =  $data->od_start_time_in_min;
             $stime = $start_time_hour.":".$start_time_min;
             $end_time_hour =  $data->od_end_time_in_hour;
             $end_time_min =  $data->od_end_time_in_min;
             $etime = $end_time_hour.":".$end_time_min;
        apply_od($date, $reason, $stime, $etime);
        var_dump(http_response_code());

        echo html_writer::div('OD has been successfully added');
        echo $OUTPUT->continue_button($CFG->wwwroot.'/blocks/leaverecord/apply_od.php');
    } else {
        $mform->display();
    }
    echo $OUTPUT->footer();
