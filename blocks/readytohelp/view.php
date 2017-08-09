<?php
/**
 * View.php
 * Display detailed information about a particular girevance to the student.
 * Display response interface for mods/teachers
 */
require_once('../../config.php');
require_once('readytohelp_form.php');
require_once('locallib.php');

// required_param('courseid', PARAM_INT);
$gid = required_param('gid', PARAM_INT);
$email = optional_param('email', null, PARAM_RAW); // Email id of responder. OR username of student
$deptid = optional_param('deptid', 0, PARAM_INT); // Deptid is referred to as categoryid in renderer.php
$hash = optional_param('hash', null, PARAM_RAW); // sha1(gid.'aybabtu'.email);
$reply = optional_param('reply', 0, PARAM_INT); // If 1, display reply form for student
$gmode = optional_param('gmode', '', PARAM_RAW); // gmode,sha1($gid.'aybabtu'.$deptid), shows all replies for review 
$action = optional_param('action', '', PARAM_RAW); // 'approve', 'disapprove'
$rid = optional_param('rid', 0, PARAM_INT); // Response id

// Check login and permissions
global $DB, $OUTPUT, $PAGE, $USER;
//require_login();
// /permissions

// Check email and hash validity
if($gmode != '' && $deptid != 0 && $gid != 0){
    // Allow reply review if gmode is valid
    $gmode = sha1($gid.'aybabtu'.$deptid) == $gmode ? $gmode : '';
}
// /check

// Special actions for mods
if( $action ){
    require_login();
    if( ($action == 'approve' || $action == 'disapprove') && $rid != 0 && $deptid != 0){
        set_response_approval($rid, $action);
        if($action == 'disapprove'){
            send_rejection_email($gid, $rid, $deptid);
        }
        // redirect(new moodle_url("$CFG->wwwroot/blocks/readytohelp/view.php?gid=$g->eid&deptid=$g->cid&gmode=$gmode"));
    } 
    else if ( $action == 'open' || $action == 'close' && $gid != 0 ){
        set_grievance_status($gid, $action);
    } 
    else if ( $action == 'remind' && $rid ) {
        // TODO Send reminder to category
    } 
    else if ( $action == 'assigndept' || $action == 'managedept' ) {
        $displayDeptForm = true;
    }
}

///Actions


// Page setup
$PAGE->set_url('/blocks/readytohelp/raise.php', array('id' => $COURSE->id));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('addgrievance', 'block_readytohelp'));


// Rendering
$output = $PAGE->get_renderer('block_readytohelp');
echo $output->header();

//check if the email hash matches
global $USER;
$is_branch_view = false;
if (isset($USER->id) && $USER->id == 0 ){
    //check hash
    $computed_hash = sha1($gid.$CFG->custom_salt.$email);
    if ($email==null || $hash == null || $computed_hash != $hash){
        //display error message
        echo html_writer::tag('h2','You do not have access to this grievance');
        exit();
    }
    $is_branch_view = true;
}

// Display student view

echo $output->grievance_detail($gid, $gmode, $branch_view = $is_branch_view);

// Display Reply form for STUDENTS ONLY
if($reply == 1 && $deptid && isset($USER->username) && $email==$USER->username){
    $replyform = new readytohelp_reply_form("view.php?gid=$gid&deptid=$deptid&email=$USER->username&reply=1",
    array(
        'grievance_id' => $gid,
        'deptid' => $deptid,
        'email' => $USER->username
    ));

    if(($data = $replyform->get_data())) {
        // Save submitted form data; Always create a new instance
        if($success = create_grievance_reply($data)) {
            // Send notifications
            redirect(new moodle_url("/blocks/readytohelp/view.php?gid=$gid&deptid=$deptid"));
        } else {
            redirect(new moodle_url("/blocks/readytohelp/feedback_page.php?action=savefailed"));
        }
    } else if ($replyform->is_cancelled()){
        // Redirect without reply form
        redirect(new moodle_url("/blocks/readytohelp/view.php?gid=$gid&deptid=$deptid"));
    } else {
        $replyform->display();
    }
}


// NOTE!! DONT DELETE!
// if(is_siteadmin()){
//     $customsalt = 'aybabtu';
//     $email='admin'; // TODO move to a different place?
//     $hash = sha1($gid.$customsalt.$email);
// }

// Display reply form for MODS and SITEADMIN
if ($email && $hash && $deptid){
    $replyform = new readytohelp_reply_form("view.php?gid=$gid&deptid=$deptid&email=$email&hash=$hash",
    array(
        'grievance_id' => $gid,
        'deptid' => $deptid,
        'email' => $email,
    ));

    if (($data = $replyform->get_data())) {
        // Save submitted form data. Create new instance every time
        if($success = create_grievance_reply($data)) {
            // TODO Display a message and redirect somewhere
            redirect(new moodle_url("/blocks/readytohelp/feedback_page.php?action=replysuccess"));
            
        } else {
            redirect(new moodle_url("/blocks/readytohelp/feedback_page.php?action=savefailed"));
        }

    } else if ($replyform->is_cancelled()){
        // TODO handle cancellation
        $replyform->display();
        redirect(new moodle_url("/blocks/readytohelp/feedback_page.php?action=replycancelled"));
    } else {
        $replyform->display();
    }
}

if (isset($displayDeptForm)) {
    $deptForm = new readytohelp_department_form("view.php?gid=$gid&action=managedept");

    if($deptForm->is_cancelled())
        redirect(new moodle_url("review_response.php"));
    
    if($data = $deptForm->get_data()){
        // Save submitted form data. Create new instance every time
        if($success = grievance_assign_department($data, $gid)) {
            batch_send_grievance_dept_emails($gid, 'new_grievance');
            redirect(new moodle_url("/blocks/readytohelp/review_response.php"));
            
        } else {
            redirect(new moodle_url("/blocks/readytohelp/review_response.php"));
        }
    }
    if($action == 'managedept'){ // Fill in details if details already present
        $deptstr = $DB->get_record('grievance_entries', array('id'=>$gid))->department;
        $departments = explode(',', $deptstr);
        $deptForm->set_data(array('departments'=>$departments));
    }
    // $DB->get_record('grievance_departments');
    $deptForm->display();
}

echo $output->footer();
