<?php
defined('MOODLE_INTERNAL') || die();


require($CFG->dirroot.'/vendor/autoload.php');
// require_once($CFG->dirroot.'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');

// Permission functions
/**
 * Check if a user has capability to review responses
 * @return boolean true if has capability. false otherwise
 */
function allow_review_responses() {
    global $USER, $DB;
    $admin = $DB->get_record('grievance_admins', array('username' => $USER->username));
    if($admin && $admin->haspermission == 1){
        return TRUE;
    } else {
        return FALSE;
    }
}

// DB related functions

// Get all categories
function get_grievance_categories(){
    global $DB;
    $result = $DB->get_records_menu('grievance_categories');
    return $result;
}

// Get email addresses of a dept
// @return (array) Emails
function get_dept_emails($deptid){
    global $DB;
    $result = $DB->get_record('grievance_categories', array('id' => $deptid));
    $emails = explode(',', $result->email);
    $formatted = array();
    foreach($emails as $e){
        $formatted[] = trim($e);
    }
    return $formatted;
}

// Create a new grievance reply by a mod
function create_grievance_reply($mform){
    global $DB;
    $mform->timecreated = time();
    if($rid = $DB->insert_record('grievance_responses', $mform)){
        return TRUE;
    } else {
        return FALSE;
    }
}

// Update an existing grievance reply
function update_grievance_reply($mform){
    global $DB;
    $mform->id = $mform->updateid;
    $mform->timecreated = time();
    return $DB->update_record('grievance_responses', $mform);
}

// Approve(1)/disapprove(0) a response
function set_response_approval($rid, $approval){
    global $DB;
    $resp = $DB->get_record('grievance_responses', array('id' => $rid));
    if($resp){
        $resp->approved = $approval == 'approve' ? 1 : -1;
        $DB->update_record('grievance_responses', $resp);
        return;
    } else {
        return;
    }
}

// Open/close a grievance
function set_grievance_status($gid, $status){
    global $DB;
    $resp = $DB->get_record('grievance_entries', array('id' => $gid));
    if($resp){
        $resp->status = $status;
        $DB->update_record('grievance_entries', $resp);
        return;
    } else {
        return;
    }
}

/** 
 * Add a new email to a department
 * @param (int) deptid - Department ID
 * @param (string) email - Email to be added to the department
 * @return (bool) TRUE(Success) / FALSE(Failure)
 */
function add_email_to_dept($deptid, $email){
    global $DB;
    $emails = get_dept_emails($deptid);
    foreach($emails as $e){
        if($e == $email){
            return TRUE;
        }
    }
    $emails[] = $email;
    $emailstr = implode(',', $emails);
    $obj = array(
        'id' => $deptid,
        'email' => $emailstr
    );
    return $DB->update_record('grievance_categories', $obj);
}

/** 
 * Remove an email from a deptartment
 * @param (int) deptid - Department ID
 * @param (string) email - Email to be added to the department
 * @return (bool) TRUE(Success) / FALSE(Failure)
 */
function remove_email_from_dept($deptid, $email){
    global $DB;
    $emails = get_dept_emails($deptid);
    $newemails = array();
    foreach($emails as $e){
        if($e != $email){ // Dont add $email if present
            $newemails[] = $e;
        }
    }
    $emailstr = implode(',', $newemails);
    $obj = array(
        'id' => $deptid,
        'email' => $emailstr
    );
    return $DB->update_record('grievance_categories', $obj);
}

// E-mail related functions

/**
 * Send out emails to ALL the members of a department linked with the specified category
 * @param (int) gid - Grievance id
 * @param (stdclass) data - should contain subject, description, deptid, category
 * @param (string) type - 'new_grievance', 'new_reply', 'reminder'.
 */
function send_grievance_dept_emails($gid, $data, $type){
    global $CFG, $DB;
    $customsalt = 'aybabtu'; // TODO Move to config
    $deptid = isset($data->deptid) ? $data->deptid : $data->category; // TODO Use deptid only
    // $emails = get_dept_emails($deptid);
    $emails = array('akshay.handrale@raoiit.com');
    $basereplyurl = $CFG->wwwroot."/blocks/readytohelp/view.php?gid=$gid&deptid=$deptid&reply=1"; // Append hash and email in sendMail

    if($type == "new_grievance" || $type == "reminder"){
        require_once('classes/emailnotification.php');
        $success = TRUE;
        foreach($emails as $email){
            $hash = sha1($gid.$customsalt.$email);
            $replyurl = $basereplyurl."&email=$email&hash=$hash#id_body";
            $task = new block_readytohelp_emailnotification();
            $task->set_custom_data(array(
                'type' => $type,
                'email' => $email,
                'subject' => $data->subject,
                'description' => $data->description,
                'replyurl' => $replyurl
            ));
            if( !$taskid = \core\task\manager::queue_adhoc_task($task) ) {
                $success = FALSE; // even if one fails
            }
        }
        return $success;
    } else if ($type == "new_reply"){
        $success = TRUE;
        require_once('classes/replynotification.php');
        foreach($emails as $email){
            $hash = sha1($gid.$customsalt.$email);
            $replyurl = $basereplyurl."&email=$email&hash=$hash#id_body";
            $task = new block_readytohelp_replynotification();
            $task->set_custom_data(array(
                'email' => $email,
                'body' => $data->body,
                'replyurl' => $replyurl
            ));
            if( !$taskid = \core\task\manager::queue_adhoc_task($task) ) {
                $success = FALSE; // even if one fails
            }
        }
        return $success;
    }
    return FALSE;
}

/**
 * Wrapper around send_greivance_dept_emails() to send reminder emails
 * @param (int) gid - Grievance Id
 * @param (int) deptid - Department ID
 * @return (bool) TRUE(Success)/FALSE(Failure) 
 */
function send_grievance_dept_reminder_emails($gid, $deptid){
    global $DB;
    $query = $DB->get_record('grievance_entries', array('id' => $gid));
    if($query) {
        $data = new stdClass();
        $data->deptid = $deptid;
        $data->subject = $query->subject;
        $data->description = $query->description;
        return $success = send_grievance_dept_emails($gid, $data, $type='reminder');
    } else {
        return FALSE;
    }
}

/**
 * Send an email to the mod whose response was rejected
 * @param (int) gid - Grievance id
 * @param (int) rid - Response id
 * @param (int) deptid - Department id
 */
function send_rejection_email($gid, $rid, $deptid){
    require_once('classes/rejectednotification.php');
    global $CFG, $DB;
    $customsalt = 'aybabtu';
    $query = $DB->get_record('grievance_entries', array('id' => $gid));
    $subject = $query->subject;
    $description = $query->description;

    $resp = $DB->get_record('grievance_responses', array('id' => $rid));
    $response = $resp->body;

    $hash = sha1($gid.$customsalt.$resp->email);
    $replyurl =  $CFG->wwwroot."/blocks/readytohelp/view.php?gid=$gid&deptid=$deptid&reply=1&email=$resp->email&hash=$hash#id_body";

    $task = new block_readytohelp_rejectednotification();
    $task->set_custom_data(array(
        'email' => $resp->email,  // of the mod
        'subject' => $subject, // of the grievance
        'description' => $description,  // of the grievance
        'response' => $response, // rejected response
        'replyurl' => $replyurl
    ));
    $yolo = \core\task\manager::queue_adhoc_task($task);
    return $yolo;
    
}
