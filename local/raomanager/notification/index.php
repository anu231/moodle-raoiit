<?php

// Handles notification - SMS and Emails

require_once('../../../config.php');
require_once('locallib.php');
require_once('../lib.php');
require_once('notification_form.php');

global $CFG, $DB, $PAGE;

$message = optional_param('message', '', PARAM_RAW);
$code = optional_param('code', -1, PARAM_INT);

$PAGE->set_url('/local/raomanager/notification/index.php');
require_login();

if(! local_raomanager_has_permission('RaoManager::Notification') )
    redirect(new moodle_url('/'));

$mform = new local_raomanager_notification_form();
$output = $PAGE->get_renderer('local_raomanager');

if( $mform->is_submitted() ){
    if ($data = $mform->get_data()) {
        $success = notification_send($data, $mform);
        if($success)
            redirect(new moodle_url('index.php?code=0'));
        else
            redirect(new moodle_url('index.php?code=1'));
    }
}

// Display feedback message
if($code != -1) {
    switch ($code) {
        case 0:
            $message = "Success! Notifications have been sent";
            break;
        case 1:
            $message = "Failed! Couldn't send Notifications. Please check if you filled out the form correctly.";
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

echo "<h1>Send notifications</h1>";
$mform->display();
echo $OUTPUT->footer();