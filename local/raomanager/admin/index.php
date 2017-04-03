<?php

require_once('../../../config.php');
require_once('admin_form.php');
require_once('locallib.php');

global $CFG, $PAGE, $DB;
require_login();
//  TODO ADD capability checks

$action = optional_param('action', '', PARAM_RAW); // Action to perform
$id = optional_param('id', 0, PARAM_INT); // Id of the record
$code = optional_param('code', -1, PARAM_INT);

$PAGE->set_heading('Raomanager:Admin');
$PAGE->set_url('/local/raomanager/admin/index.php');

$mform = new local_raomanager_admin_form();
$output = $PAGE->get_renderer('local_raomanager');

// Save/Edit
if( $mform->is_submitted() ){
    if ($data = $mform->get_data()) {
        if (isset($data->id) && $data->id != 0) {
            // Update existing
            $success = rm_admin_edit($data, $mform);
            if($success)
                redirect(new moodle_url('index.php?action=view&code=0'));
            else
                redirect(new moodle_url('index.php?action=view&code=2'));
        }
        else {
            // New record
            $success = rm_admin_add($data, $mform);
            if($success)
                redirect(new moodle_url('index.php?action=view&code=0'));
            else
                redirect(new moodle_url('index.php?action=view&code=1'));
        }
    }
}
// Delete
if ($action == 'delete' && $id != 0) { 
    $success = rm_admin_delete($id);
    if ($success)
        redirect(new moodle_url('index.php?action=view&code=0'));
    else
        redirect(new moodle_url('index.php?action=view&code=3'));
}



// Display feedback message
if($code != -1) {
    switch ($code) {
        case 0:
            $message = "Action successful";
            break;
        case 1:
            $message = "Action Failed";
            break;
        default:
            $message = "You hacker you!";
            break;
    }
}


echo $OUTPUT->header();
if(isset($message)) {
    $dialog = new dialog($message, $code); // Dialog box
    echo $output->render($dialog);
}

if ($action == 'add') {
    $mform->display();
} else if ($action == 'edit' && $id != 0) {
    $item = $DB->get_record('raomanager_admins', array('id'=>$id));
    $mform->set_data($item);
    $mform->display();
} else {
    echo $output->admin_info();
}

echo $OUTPUT->footer();

