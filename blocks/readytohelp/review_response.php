<?php

require_once('../../config.php');
require_once('../../local/raomanager/lib.php');
require_once('locallib.php');
require_once('readytohelp_form.php');

$gid = optional_param('gid', 0, PARAM_INT); // Grievance id
$rid = optional_param('rid', 0, PARAM_INT); // Response id
$action = optional_param('action', '', PARAM_RAW); // 'assign', 'remove', etc
$email = optional_param('email', '', PARAM_RAW); // Email to be acted upon
$deptid = optional_param('deptid', 0, PARAM_INT);
$message = optional_param('message', '', PARAM_RAW); // Display message if any
$name = optional_param('name', '', PARAM_RAW); // Name of new department

require_login(); // TODO Required for setting context
global $OUTPUT, $PAGE, $USER, $COURSE;

if ( ! local_raomanager_has_permission('ReadyToHelp') ){
    // Redirect to index
    redirect(new moodle_url('/'));
}

function redirect_self($params=null, $message=null){
    // Redirect back to this page. Used after performing actions
    // global $courseid; // Required due to strange php scoping issue
    $url_params = "";
    $url_params .= $params != null ? "&$params" : ""; // Arbitary params
    if($message) {
        $url_params .= "&message=$message"; // Display Message if any
    }

    redirect(new moodle_url("review_response.php?&$url_params"));
}
if($action){
    // Various actions that can be performed
    if($action == 'assign' && $deptid != 0 && $email != ''){
        // Assign an email to a dept
        if( $success = add_email_to_dept($deptid, $email) ) {
            $msg = 'Your action was successful!';
            redirect_self('action=managermode', $msg);
        } else {
            $msg = 'Your action failed!';
            redirect_self('action=managermode', $msg);
        }
    }
    if($action == 'fire' && $deptid != 0 && $email != ''){
        // Remove an email from a dept
        if( $success = remove_email_from_dept($deptid, $email) ) {
            $msg = 'Your action was successful!';
            redirect_self('action=managermode', $msg);
        } else {
            $msg = 'Your action failed!';
            redirect_self('action=managermode', $msg);
        }
    }
    if($action == 'remind' && $gid != 0){
        // Send a reminder email to a department members
        if ($success = batch_send_grievance_dept_emails($gid, 'reminder')) {
            $msg = 'Your action was successful!';            
            redirect_self(null, $msg);
        } else {
            $msg = 'Your action failed';
            redirect_self(null, $msg);
        }
    }
    if($action == 'adddept' && $name != ''){
        // Add A new Department
        if ($success = add_new_dept($name)) {
            $msg = 'Your action was successful!';
            redirect_self("action=managermode", $msg);
        } else {
            $msg = 'Your action failed';
            redirect_self("action=managermode", $msg);            
        }
    }
    if($action == 'rmdept' && $deptid != 0){
        // Remove A new Department
        if ($success = remove_dept($deptid)) {
            $msg = 'Your action was successful!';
            redirect_self("action=managermode", $msg);
        } else {
            $msg = 'Your action failed';
            redirect_self("action=managermode", $msg);            
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