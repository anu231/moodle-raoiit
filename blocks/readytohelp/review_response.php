<?php

require_once('../../config.php');
require_once('../../local/raomanager/lib.php');
require_once('locallib.php');

$courseid = required_param('cid', PARAM_INT); // Course ID for capability check
$gid = optional_param('gid', 0, PARAM_INT); // Grievance id
$rid = optional_param('rid', 0, PARAM_INT); // Response id
$action = optional_param('action', '', PARAM_RAW); // 'assign', 'remove', etc
$email = optional_param('email', '', PARAM_RAW); // Email to be acted upon
$deptid = optional_param('deptid', 0, PARAM_INT);
$message = optional_param('message', '', PARAM_RAW); // Display message if any

require_login($courseid); // Required for setting context
global $OUTPUT, $PAGE, $USER, $COURSE;

if ( ! local_raomanager_has_permission('ReadyToHelp') ){
    // Redirect to index
    redirect(new moodle_url('/'));
}

function redirect_self($params=null, $message=null){
    // Redirect back to this page. Used after performing actions
    global $courseid; // Required due to strange php scoping issue
    $url_params = "";
    $url_params .= $params != null ? "&$params" : ""; // Arbitary params
    if($message) {
        $url_params .= "&message=$message"; // Display Message if any
    }

    redirect(new moodle_url("review_response.php?cid=$courseid&$url_params"));
}
if($action){
    // Various actions that can be performed
    if($action == 'assign' && $deptid != 0 && $email != ''){
        // Assign an email to a dept
        if( $success = add_email_to_dept($deptid, $email) ) {
            $msg = 'Success!';
            redirect_self('action=managermode', $msg);
        } else {
            $msg = 'Failed!';
            redirect_self('action=managermode', $msg);
        }
    }
    if($action == 'fire' && $deptid != 0 && $email != ''){
        // Remove an email from a dept
        if( $success = remove_email_from_dept($deptid, $email) ) {
            $msg = 'Success!';
            redirect_self('action=managermode', $msg);
        } else {
            $msg = 'Failed!';
            redirect_self('action=managermode', $msg);
        }
    }
    if($action == 'remind' && $deptid != 0 && $gid != 0){
        // Send a reminder email to a department
        if ($success = send_grievance_dept_reminder_emails($gid, $deptid)) {
            $msg = 'Success!';            
            redirect_self(null, $msg);                        
        } else {
            $msg = 'Failed';
            redirect_self(null, $msg);            
        }
    }

}

// Page setup
$PAGE->set_url('/blocks/readytohelp/review_response.php');
$PAGE->set_heading(get_string('addgrievance', 'block_readytohelp'));

// Rendering
$output = $PAGE->get_renderer('block_readytohelp');

echo $output->header();

if( $message ) {
    echo $output->heading($message);
}

if( $action == 'managermode'){
    echo $output->manage_departments();
} else {
    echo $output->grievance_responses();
}

echo $output->footer();