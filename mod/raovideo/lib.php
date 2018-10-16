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
    $raovideo->name = $GLOBALS['sectionname'];
    if($customname = $raovideo->name)
        $raovideo->name = $customname;
    $id = $DB->insert_record('raovideo', $raovideo);
    return $id;
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