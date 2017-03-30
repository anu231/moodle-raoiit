<?php
require_once("$CFG->dirroot/user/profile/lib.php");

// Add a new batch
function batch_add($batch, $mform = NULL) {
    global $DB, $USER;
    $batch->timecreated = time();
    $batch->assignee = $USER->username;
    if ($id = $DB->insert_record('raomanager_batches', $batch))
        return $id;
    else 
        return FALSE;
}

// edit an existing batch
function batch_edit($batch, $mform = NULL) {
    global $DB, $USER;
    $batch->id;
    $batch->timemodified = time();
    $batch->assignee = $USER->username;
    if ($success = $DB->update_record('raomanager_batches', $batch))
        return $success;
    else 
        return FALSE;
}

// Delete a batch record
function batch_delete($id) {
    global $DB, $USER;
    $success = $DB->delete_records('raomanager_batches', array('id'=>$id));
    return $success;
}


// Batch sms's' and add them to moodle tasks
function notification_send_sms($body, $numbers){
    require_once('classes/notificationsms.php');
    global $DB, $CFG, $USER;
    $task = new local_raomanager_notificationsms();
    $task->set_custom_data(array(
        'numbers' => $numbers,
        'body' => $body
    ));
    $taskid = \core\task\manager::queue_adhoc_task($task);
}


// Batch emails and add them to moodle tasks
function notification_send_email($subject, $body, $emails){
    require_once('classes/notificationemails.php');
    global $DB, $CFG, $USER;

    $batch = array();
    for ($i=0; $i < count($emails); $i++) {
        $email = $emails[$i]; 
        if( count($batch) < 20 && $i != count($emails) - 1){
            $batch[] = $email;
            continue;
        }
        $batch[] = $email;
        $task = new local_raomanager_notificationemails();
        $task->set_custom_data(array(
            'emails' => $batch,
            'subject' => $subject,
            'body' => $body
        ));
        $taskid = \core\task\manager::queue_adhoc_task($task);
        $batch = array();
    }
}

// Filter and choose the appropriate function to call
function notification_send($notif) {
    global $DB, $CFG, $USER;
    
    $batches = isset($notif->batches) ? $notif->batches : '*';
    $centers = isset($notif->centers) ? $notif->centers : '*';
    $years = isset($notif->years) ? $notif->years : '*';
    $filter = array('centers' => $centers, // Query filter
                    'batches' => $batches,
                    'targetyears' => $years
                    );
    $recipient = $notif->recipient; // 0 = students. 1 = students + parents

    $students = notification_get_profiles($filter);

    if($notif->medium == 0){
        // Send sms
        $numbers = array(); // To be notified
        
        if($notif->recipient == 0){ // Student only
            foreach ($students as $student) {
                if($student->studentmobile != "")
                    $numbers[] = $student->studentmobile;
            }
        }
        else if ($notif->recipient == 1){ // Student and parent
            foreach ($students as $student) {
                if($student->studentmobile != "")
                    $numbers[] = $student->studentmobile;
                if($student->fathermobile != "")
                    $numbers[] = $student->fathermobile;
                if($student->mothermobile != "")
                    $numbers[] = $student->mothermobile;
            }
        }
        $body = $notif->smsbody;
        notification_send_sms($body, $numbers);
        return TRUE;
    }

    // EMAIL!
    else if ($notif->medium == 1){
        // Send email
        $emails = array(); // To be notified

        if($notif->recipient == 0){ // Student only
            foreach ($students as $student){
                if($student->studentemail != "")            
                    $emails[] = $student->studentemail;
            }
        }
        else if ($notif->recipient == 1){ // Student and parent
            foreach ($sutdents as $student) {
                if($student->studentemail != "")
                    $emails[] = $student->studentemail;
                if($student->fatheremail != "")
                    $emails[] = $student->fatheremail;
                if($student->motheremail != "")
                    $emails[] = $student->motheremail;
            }
        }
        $subject = $notif->emailsubject;
        $body = $notif->emailbody;
        notification_send_email($subject, $body, $emails);
        return TRUE;
    }
}

// Returns filtered array of user profile records
function notification_get_profiles($filter=null) {
    global $DB;
    $filteredusers = array();
    $users = $DB->get_records('user');
    foreach ($users as $user) {
        $userinfo = profile_user_record($user->id);
        if($filter['targetyears'] != '*')
            if(!in_array($userinfo->targetyear, $filter['targetyears']))
                continue;
        if($filter['centers'] != '*')
            if(!in_array($userinfo->center, $filter['centers']))
                continue;
        if($filter['batches'] != '*')
            if(!in_array($userinfo->batch, $filter['batches']))
                continue;
        // Passed all filter tests
        $userinfo->username = $user->username; // Insert username for reference
        $filteredusers[] = $userinfo;
    }
    return $filteredusers;

}