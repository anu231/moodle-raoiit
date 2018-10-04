<?php


function raovideo_supports($feature) {
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

function raovideo_add_instance($raovideo, $mform=NULL){
    global $DB, $CFG, $USER, $COURSE, $GLOBALS;
    // exp
    $raovideo->timecreated = time();
    $raovideo->asignee = $USER->username;

    if($raovideo->videoid != 0){
        // Assign an existing booklet(booklet != 0)
        // $raovideo->name = $DB->get_record('raovideo_info', array('videoid' => $raovideo->bookletid))->name;
        $raovideo->name = $GLOBALS['sectionname'];
        if($customname = $raovideo->name)
            $raovideo->name = $customname;
        $id = $DB->insert_record('raovideo', $raovideo);
        return $id;
    } else {
        // Upload a new booklet and assign it immediately
        // Save attachment
        $context = context_system::instance();
        $entryid = 0; // All files have entryid 0. DO NOT CHANGE
        $filename = $mform->get_new_filename('attachment');
        $file = $mform->save_stored_file('attachment',$context->id, 'mod_raovideo', 'uploads', 0 );

        // Get saved file info
         $raovideo->name = $filename;
         $raovideo->videoid = $file->get_id(); // Override bookletid(0) with $fileid. (This is how it's designed)
         $DB->insert_record('rao_video_info', $raovideo); // Save metadata

         // Change instance name
        $raovideo->name = $GLOBALS['sectionname'];
        if($customname = $raovideo->customname)
            $raovideo->name = $customname;
        $id = $DB->insert_record('raovideo', $raovideo); // Assign booklet
        return $id;
    }
}


function raovideo_update_instance($raovideo, $mform=NULL){
    global $DB, $CFG, $USER, $GLOBALS;
    $raovideo->id = $raovideo->instance;
    $raovideo->timemodified = time();
    $raovideo->name = $GLOBALS['sectionname'];
    if($customname = $raovideo->customname)
        $raovideo->name = $customname;
    if($raovideo->videoid != 0){
        $success = $DB->update_record('raovideo', $raovideo);
        return $success;
    }
    return FALSE;
}


function raovideo_delete_instance($id){
    //TODO delete associated file?
    return true;
}

/**
* Creates a new moodle ad hoc task for converting pdf to images.
* (note: does not manage directories. Making/deleting directories is handled by the create/update methods)
*
* @param $raovideo instance ( Used only for filename )
* @param $dir base directory for storing file
* @return bool TRUE/FALSE for success/failure
*/
function raovideo_convert_pdf($raovideo, $basedir) {
    try {
        $task = new mod_raovideo_pdf2jpg();
        $task->set_custom_data(array(
            'path' => $basedir, // includes trailing '/'
            'name' => $raovideo->filename
        ));
        \core\task\manager::queue_adhoc_task($task);
        return TRUE;
    } catch (Exception $e) {
        echo "Exception occured while creating raovideo pdf creation : ".var_dump($e->getMessage());
        return FALSE;
    }
}


/**
 * Update the metadata about uploaded booklets
 */
function raovideo_edit_info($raovideo, $mform=NULL){
    global $DB, $CFG, $USER;

    if(isset($raovideo->id)){
        $success = $DB->update_record('raovideo_info', $raovideo);
        return $success;
    } else {
        // new instance
        $id = $DB->insert_record('raovideo_info', $raovideo);
        return $id;
    }
}


/**
* Add feedback
* @param $id int raovideo instanceof
* @param $rating int Rating out of 5
* @param $comment str Comment
* @return bool Success/Failure
*/
function raovideo_add_or_update_feedback($videoid, $feedback) {
    global $DB, $USER;
    if($fb = $DB->get_record('raovideo_feedback', array('userid'=> $USER->id))) {
        $fb->rating = $feedback->rating;
        $fb->comment = $feedback->comment;
        $fb->timemodified = time();
        $result = $DB->update_record('raovideo_feedback', $fb);
        return $result;
    } else {
        // Setup feedback object
        $feedback = new stdclass();
        $feedback->videoid = $videoid;
        $feedback->userid = $USER->id;
        $feedback->timecreated = time();
        $result = $DB->insert_record('raovideo_feedback', $feedback);
        if ( $result ){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
