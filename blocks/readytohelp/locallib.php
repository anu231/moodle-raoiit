<?php
defined('MOODLE_INTERNAL') || die();


require($CFG->dirroot.'/vendor/autoload.php');
// require_once($CFG->dirroot.'/vendor/phpmailer/phpmailer/PHPMailerAutoload.php');

// Permission functions
/** DEPRICATED! USE local_raomanager_has_permission('ReadyToHelp') instead;
 * Check if a user has capability to review responses
 * @return boolean true if has capability. false otherwise
 */
function allow_review_responses() {
    global $USER, $DB;
    $admin = $DB->get_record('grievance_admins', array('username' => $USER->username));
    if(($admin && $admin->haspermission == 1) || ($USER->username == 'admin')){
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

// Get all departments
function get_grievance_departments(){
    global $DB;
    $result = $DB->get_records_menu('grievance_departments');
    return $result;
}

// Manage departments assigned to grievance
function grievance_assign_department($data, $gid){
    global $DB;
    $grievance = $DB->get_record('grievance_entries', array('id' => $gid));
    $deptstr = implode(',', $data->departments);
    $grievance->department = $deptstr;
    return $DB->update_record('grievance_entries', $grievance);
}

// Get email addresses of a dept
// @return (array) Emails
function get_dept_emails($deptid){
    global $DB;
    $result = $DB->get_record('grievance_departments', array('id' => $deptid));
    $formatted = array();
    if($result){
        $emails = explode(',', $result->email);
        foreach($emails as $e){
            if($e)
                $formatted[] = trim($e);
        }
    }
    return $formatted;
}

// Create a new grievance reply by a mod
function create_grievance_reply($mform){
    global $DB;
    $mform->timecreated = time();
    if($mform->email == 'admin') // All admin replies are preapproved by the Lord!
        $mform->approved = 1;
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
// Also send an email to the user about the reply
function set_response_approval($rid, $approval){
    global $DB;
    $resp = $DB->get_record('grievance_responses', array('id' => $rid));
    if($resp){
        $resp->approved = $approval == 'approve' ? 1 : -1;
        $success = $DB->update_record('grievance_responses', $resp);
        if ($success && $approval == 'approve') {
            $success = notify_student($resp);
            if (! $success )
                return FALSE;
        }
        return TRUE;
    } else {
        return FALSE;
    }
}

// Open/close a grievance
function set_grievance_status($gid, $status){
    global $DB;
    $resp = $DB->get_record('grievance_entries', array('id' => $gid));
    if($resp){
        $resp->status = $status == 'close' ? 'closed' : 'open';
        $DB->update_record('grievance_entries', $resp);
        return;
    } else {
        return;
    }
}

function add_new_dept($name){
    global $DB;
    $dept = new stdclass;
    $dept->name = $name;
    return $DB->insert_record('grievance_departments', $dept);
}

function remove_dept($deptid){
    global $DB;
    return $DB->delete_records('grievance_departments', array('id'=>$deptid));
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
    return $DB->update_record('grievance_departments', $obj);
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
    return $DB->update_record('grievance_departments', $obj);
}

// E-mail related functions

/** !!!NOTE: Do not call this method directly. Use wrappers in stead. This is because this method
 * only accepts ONE department id!
 * Send out emails to ALL the members of a department linked with the specified category
 * @param (int) gid - Grievance id
 * @param (stdclass) data - should contain subject, description, deptid, category
 * @param (string) type - 'new_grievance', 'new_reply', 'reminder'.
 */
function _send_grievance_dept_emails($gid, $data, $type){
    global $CFG, $DB;
    $customsalt = $CFG->custom_salt; // TODO Move to config
    $deptid = $data->deptid;
    $emails = get_dept_emails($deptid);
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
            $replyurl = $basereplyurl."&email=$email&hash=$hash";
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
 * @return (bool) TRUE(Success)/FALSE(Failure) 
 */
function batch_send_grievance_dept_emails($gid, $type){
    global $DB;
    $query = $DB->get_record('grievance_entries', array('id' => $gid));
    if($query) {
        if(!$query->department)
            return FALSE;
        $departments = explode(',', $query->department);
        foreach($departments as $dept){
            $data = new stdClass();
            $data->deptid = $dept;
            $data->subject = $query->subject;
            $data->description = $query->description;
            $success = _send_grievance_dept_emails($gid, $data, $type);
        }
        return TRUE;
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
    $resp = $DB->get_record('grievance_responses', array('id' => $rid));
    if($resp->email == 'admin') // Dont send rejection mails for admin
        return 0;
    $response = $resp->body;
    $query = $DB->get_record('grievance_entries', array('id' => $gid));
    $subject = $query->subject;
    $description = $query->description;


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

/**
 * Send an email to student about a new approved response
 */

function notify_student($resp) {
    global $DB, $CFG;
    require_once('classes/studentnotification.php');
    require_once("$CFG->dirroot/user/profile/lib.php");
    // Get student's email'
    $gid = $resp->grievance_id;
    $grievance = $DB->get_record('grievance_entries', array('id' => $gid));
    $user = $DB->get_record('user', array('username' => $grievance->username));
    $studentemail = $user->email;
    if ($studentemail == "")
        return FALSE;

    $viewurl = $CFG->wwwroot."/blocks/readytohelp/view.php?gid=$gid";

    $task = new block_readytohelp_studentnotification();
    $task->set_custom_data(array(
        'email' => $studentemail,
        'grievance' => $grievance,
        'response' => $resp,
        'viewurl' => $viewurl
    ));
    if( !$taskid = \core\task\manager::queue_adhoc_task($task) ) {
        return FALSE;
    }
    return TRUE;
}

function send_grievance_notification_admin($data){
    global $CFG;
    $grievance_categories = get_grievance_categories();
    $category = $grievance_categories[$data->category];
    $email_text = <<<EOT
    Roll No - $data->username<br>
    Category - $category<br>
    Subject - $data->subject<br>
    Descirption - $data->description<br>
EOT;
    $task = new block_readytohelp_emailnotification();
    $task->set_custom_data(array(
        'email' => $CFG->grievance_admin_emails,
        'type' => 'admin-notification',
        'subject' => $data->username,
        'description' => $email_text,
        'replyurl' => 'Not Provided'
    ));
    if( !$taskid = \core\task\manager::queue_adhoc_task($task) ) {
        return FALSE;
    }
    return TRUE;
}

function setup_phpmailer(){
    global $CFG;
    $mail = new PHPMailer;
    // $mail->SMTPDebug = 3;                               // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = $CFG->mail_smtp_host;  // Specify main and backup SMTP servers
    $mail->SMTPAuth = $CFG->mail_smtp_auth;                               // Enable SMTP authentication
    $mail->Username = $CFG->mail_username;                 // SMTP username
    $mail->Password = $CFG->mail_passwd;                           // SMTP password
    // $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = $CFG->mail_port;                                    // TCP port to connect to
    $mail->SMTPOptions = array(
        'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
        )
    );
    return $mail;
}