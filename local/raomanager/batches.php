<?php

// Manage batches

require_once('../../config.php');
require_once('lib.php');
require_once('batch_form.php');

global $CFG, $DB, $PAGE;
$PAGE->set_url('/local/raomanager/batches.php');

require_login();

$action = optional_param('action', '', PARAM_RAW); // Action to perform
$batchid = optional_param('batchid', 0, PARAM_INT); // Data to be acted upon
$error = optional_param('error', 0, PARAM_INT); // Data to be acted upon

$mform = new local_raomanager_batch_form();
$output = $PAGE->get_renderer('local_raomanager');

// Save/Edit
if ($mform->is_submitted()){
    if ($data = $mform->get_data()) {
        if (isset($data->id) && $data->id != 0)
            $success = edit_batch($data, $mform);
        else
            $success = add_batch($data, $mform);
        if($success)
            redirect(new moodle_url('batches.php?action=view'));
        else
            redirect(new moodle_url('batches.php?action=view&error=1'));
    }
}
// Delete
if ($action == 'delete' && $batchid != 0) { 
    $success = delete_batch($batchid);
    if ($success)
        redirect(new moodle_url('batches.php?action=view'));
    else
        redirect(new moodle_url('batches.php?action=view&error=2'));
}


// Rendering
echo $output->header();

// Display error message
if($error != 0)
    echo $output->error($error);

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