<?php
 
require_once('../../config.php');
require_once('readytohelp_form.php');
 
global $DB, $OUTPUT, $PAGE, $USER;
 
// Check for all required variables.
//$courseid = required_param('courseid', PARAM_INT);
$blockid = required_param('blockid', PARAM_INT); 
 
/*if (!$course = $DB->get_record('course', array('id' => $courseid))) {
    print_error('invalidcourse', 'block_simplehtml', $courseid);
}
 
require_login($course);*/
require_login();
$PAGE->set_url('/blocks/readytohelp/raise.php');

// $courseid = 2;

// $PAGE->set_url('/blocks/readytohelp/view.php', array('id' => $courseid));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('addgrievance', 'block_readytohelp'));

$grievance_form = new readytohelp_create_form();

if ($grievance_form->is_cancelled()) {
    redirect(new moodle_url('/blocks/readytohelp/list.php'));
} else if (($data=$grievance_form->get_data())){
    $data->username = $USER->username;
    $data->timecreated = time();
    $data->status='open';
    if (($gid=$DB->insert_record('grievance_entries',$data))){
        //redirect to list of grievances
        $view_url = new moodle_url('/blocks/readytohelp/list.php');
        //send email to admin about the grievance
        send_grievance_notification_admin($data);
        redirect($view_url);
    } else {
        print_error('inserterror', 'block_readytohelp');
        redirect(new moodle_url("/blocks/readytohelp/feedback_page.php?action=savefailed2"));
    }
} else {
    $site = get_site();
    $toform['blockid'] = $blockid;
    $grievance_form->set_data($toform);

    echo $OUTPUT->header();
    $grievance_form->display();
    echo $OUTPUT->footer();
}
