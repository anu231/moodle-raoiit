<?php

// Manage Centers

require_once('../../../config.php');
require_once('locallib.php');
require_once('center_form.php');

global $CFG, $DB, $PAGE;
$PAGE->set_heading('Raomanager:Centers');

require_login();
if(! local_raomanager_has_permission('RaoManager::Center') )
    redirect(new moodle_url('/'));

$action = optional_param('action', '', PARAM_RAW); // Action to perform
$centerid = optional_param('centerid', 0, PARAM_INT); // Data to be acted upon
$code = optional_param('code', -1, PARAM_INT);

$mform = new local_raomanager_center_form();
$output = $PAGE->get_renderer('local_raomanager');

// Save/Edit
if ($mform->is_submitted()){
    if ($data = $mform->get_data()) {
        if (isset($data->id) && $data->id != 0) {
            // Update existing
            $success = rm_center_edit($data, $mform);
            if($success)
                redirect(new moodle_url('index.php?action=view&code=0'));
            else
                redirect(new moodle_url('index.php?action=view&code=2'));
        }
        else {
            // New record
            $success = rm_center_add($data, $mform);
            if($success)
                redirect(new moodle_url('index.php?action=view&code=0'));
            else
                redirect(new moodle_url('index.php?action=view&code=1'));
        }
    }
}
// Delete
if ($action == 'delete' && $centerid != 0) { 
    $success = rm_center_delete($centerid);
    if ($success)
        redirect(new moodle_url('index.php?action=view&code=0'));
    else
        redirect(new moodle_url('index.php?action=view&code=3'));
}

// Display feedback message
if($code != -1) {
    switch ($code) {
        case 0:
            $message = "Your action was successful!";
            break;
        case 1:
            $message = "Your Action Failed";
            break;
        default:
            $message = "You hacker you!";
            break;
    }
}


// Rendering
echo $output->header();

if(isset($message)) {
    $dialog = new dialog($message, $code); // Dialog box
    echo $output->render($dialog);
}

// Form
if ($action == 'add') {
    $mform->display();
} else if ($action == 'edit' && $centerid != 0) {
    $item = $DB->get_record('raomanager_centers', array('id'=>$centerid));
    $mform->set_data($item);
    $mform->display();
} else {
    echo $output->center_info();
}

echo $output->footer();