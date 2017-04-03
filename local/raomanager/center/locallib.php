<?php
// Add a new center
function rm_center_add($center, $mform = NULL) {
    global $DB, $USER;
    $center->timecreated = time();
    $center->assignee = $USER->username;
    if ($id = $DB->insert_record('raomanager_centers', $center))
        return $id;
    else 
        return FALSE;
}

// edit an existing center
function rm_center_edit($center, $mform = NULL) {
    global $DB, $USER;
    $center->id;
    $center->timemodified = time();
    $center->assignee = $USER->username;
    if ($success = $DB->update_record('raomanager_centers', $center))
        return $success;
    else 
        return FALSE;
}

// Delete a center record
function rm_center_delete($id) {
    global $DB, $USER;
    $success = $DB->delete_records('raomanager_centers', array('id'=>$id));
    return $success;
}
