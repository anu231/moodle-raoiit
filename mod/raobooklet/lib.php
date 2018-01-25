<?php


function raobooklet_supports($feature) {
    switch($feature) {
        //case FEATURE_GROUPS:                  return true;
        //case FEATURE_GROUPINGS:               return true;
        //case FEATURE_MOD_INTRO:               return true;
        //case FEATURE_COMPLETION_TRACKS_VIEWS: return true;
        //case FEATURE_COMPLETION_HAS_RULES:    return true;
        //case FEATURE_GRADE_HAS_GRADE:         return false;
        //case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_BACKUP_MOODLE2:          return true;
        //case FEATURE_SHOW_DESCRIPTION:        return true;

        default: return null;
    }
}

function raobooklet_add_instance($raobooklet, $mform=NULL){
    global $DB, $CFG, $USER, $COURSE, $GLOBALS;
    // exp
    $raobooklet->timecreated = time();
    $raobooklet->asignee = $USER->username;

    if($raobooklet->bookletid != 0){
        // Assign an existing booklet(booklet != 0)
        // $raobooklet->name = $DB->get_record('raobooklet_info', array('bookletid' => $raobooklet->bookletid))->name;
        $raobooklet->name = $GLOBALS['sectionname'];
        if($customname = $raobooklet->customname)
            $raobooklet->name = $customname;
        $id = $DB->insert_record('raobooklet', $raobooklet);
        return $id;
    } else {
        // Upload a new booklet and assign it immediately
        // Save attachment
        $context = context_system::instance();
        $entryid = 0; // All files have entryid 0. DO NOT CHANGE
        $filename = $mform->get_new_filename('attachment');
        $file = $mform->save_stored_file('attachment',$context->id, 'mod_raobooklet', 'uploads', 0 );

        // Get saved file info
        $raobooklet->name = $filename;
        $raobooklet->bookletid = $file->get_id(); // Override bookletid(0) with $fileid. (This is how it's designed)
        $DB->insert_record('raobooklet_info', $raobooklet); // Save metadata
        // Change instance name
        $raobooklet->name = $GLOBALS['sectionname'];
        if($customname = $raobooklet->customname)
            $raobooklet->name = $customname;
        $id = $DB->insert_record('raobooklet', $raobooklet); // Assign booklet
        return $id;
    }
}


function raobooklet_update_instance($raobooklet, $mform=NULL){
    global $DB, $CFG, $USER, $GLOBALS;
    $raobooklet->id = $raobooklet->instance;
    $raobooklet->timemodified = time();
    $raobooklet->name = $GLOBALS['sectionname'];
    if($customname = $raobooklet->customname)
        $raobooklet->name = $customname;
    if($raobooklet->bookletid != 0){
        $success = $DB->update_record('raobooklet', $raobooklet);
        return $success;
    }
    return FALSE;
}


function raobooklet_delete_instance($id){
    //TODO delete associated file?
    return true;
}

/**
* Creates a new moodle ad hoc task for converting pdf to images.
* (note: does not manage directories. Making/deleting directories is handled by the create/update methods)
*
* @param $raobooklet instance ( Used only for filename )
* @param $dir base directory for storing file
* @return bool TRUE/FALSE for success/failure
*/
function raobooklet_convert_pdf($raobooklet, $basedir) {
    try {
        $task = new mod_raobooklet_pdf2jpg();
        $task->set_custom_data(array(
            'path' => $basedir, // includes trailing '/'
            'name' => $raobooklet->filename
        ));
        \core\task\manager::queue_adhoc_task($task);
        return TRUE;
    } catch (Exception $e) {
        echo "Exception occured while creating raobooklet pdf creation : ".var_dump($e->getMessage());
        return FALSE;
    }
}


/**
 * Update the metadata about uploaded booklets
 */
function raobooklet_edit_info($raobooklet, $mform=NULL){
    global $DB, $CFG, $USER;

    if(isset($raobooklet->id)){
        $success = $DB->update_record('raobooklet_info', $raobooklet);
        return $success;
    } else {
        // new instance
        $id = $DB->insert_record('raobooklet_info', $raobooklet);
        return $id;
    }
}


/**
* Add feedback
* @param $id int Raobooklet instanceof
* @param $rating int Rating out of 5
* @param $comment str Comment
* @return bool Success/Failure
*/
function raobooklet_add_or_update_feedback($bookletid, $feedback) {
    global $DB, $USER;
    if($fb = $DB->get_record('raobooklet_feedback', array('userid'=> $USER->id))) {
        $fb->rating = $feedback->rating;
        $fb->comment = $feedback->comment;
        $fb->timemodified = time();
        $result = $DB->update_record('raobooklet_feedback', $fb);
        return $result;
    } else {
        // Setup feedback object
        $feedback = new stdclass();
        $feedback->bookletid = $bookletid;
        $feedback->userid = $USER->id;
        $feedback->timecreated = time();
        $result = $DB->insert_record('raobooklet_feedback', $feedback);
        if ( $result ){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
