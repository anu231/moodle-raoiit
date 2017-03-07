<?php

/**
 * feedback_page.php
 * Landing page for displaying feedback/messages to the mods and students
 * @param action - key for the message to be displayed
 */
require_once('../../config.php');

$action = required_param('action', PARAM_RAW);
global $DB, $OUTPUT, $PAGE, $USER;

// Set $message variable according to $action
switch ($action) {
    case 'replysuccess':
        $message = "Your reply has been submitted and is now waiting for approval.<br><br>
        In case the reply is not found to be suitable, you will be notified by email.";
        break;
    
    case 'replycancelled':
        $message = "Cancelled: To try again, click on the
        link in the email sent to you.";
        break;
    
    case 'savefailed':
        $message = "Couldn't save your reply. Please try again.";
        break;
    
    case 'savefailed2':
        $message = "Couldn't raise grievance now. Please try again later.";
        break;

    default:
        $message = "The cake is a lie.";
        break;
}




// Page setup
$PAGE->set_url('/blocks/readytohelp/feedback_page.php', array('id' => $COURSE->id));
$PAGE->set_pagelayout('standard');
$PAGE->set_title('Done');
$PAGE->set_heading(get_string('addgrievance', 'block_readytohelp'));

echo $OUTPUT->header();
echo <<<HTML
    <h4>
        $message
    </h4>
HTML;
echo $OUTPUT->footer();
// Rendering
