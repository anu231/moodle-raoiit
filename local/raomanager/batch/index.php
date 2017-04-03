<?php

// Manage batches

require_once('../../../config.php');
require_once('locallib.php');
require_once('batch_form.php');

global $CFG, $DB, $PAGE;
$PAGE->set_heading('Raomanager:Batches');

require_login();
if(! local_raomanager_has_permission('RaoManager::Batch') )
    redirect(new moodle_url('/'));

$action = optional_param('action', '', PARAM_RAW); // Action to perform
$batchid = optional_param('batchid', 0, PARAM_INT); // Data to be acted upon
$code = optional_param('code', -1, PARAM_INT);

$mform = new local_raomanager_batch_form();
$output = $PAGE->get_renderer('local_raomanager');

// Save/Edit
if ($mform->is_submitted()){
    if ($data = $mform->get_data()) {
        if (isset($data->id) && $data->id != 0) {
            // Update existing
            $success = rm_batch_edit($data, $mform);
            if($success)
                redirect(new moodle_url('index.php?action=view&code=0'));
            else
                redirect(new moodle_url('index.php?action=view&code=2'));
        }
        else {
            // New record
            $success = rm_batch_add($data, $mform);
            if($success)
                redirect(new moodle_url('index.php?action=view&code=0'));
            else
                redirect(new moodle_url('index.php?action=view&code=1'));
        }
    }
}
// Delete
if ($action == 'delete' && $batchid != 0) { 
    $success = rm_batch_delete($batchid);
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
            $message = "Couldn't Create a New Batch (Error 1)";
            break;
        case 2:
            $message = "Couldn't Save Changes to batch (Error 2)";
            break;
        case 3:
            $message = "Couldn't Delete the Batch (Error 3)";
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
} else if ($action == 'edit' && $batchid != 0) {
    $item = $DB->get_record('raomanager_batches', array('id'=>$batchid));
    $mform->set_data($item);
    $mform->display();
} else {
    echo $output->batch_info();
}

echo $output->footer();