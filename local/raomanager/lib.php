<?php

// Add a new batch
function add_batch($batch, $mform = NULL) {
    global $DB, $USER;
    $batch->timecreated = time();
    $batch->assignee = $USER->username;
    if ($id = $DB->insert_record('raomanager_batches', $batch))
        return $id;
    else 
        return FALSE;
}

// edit an existing batch
function edit_batch($batch, $mform = NULL) {
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
function delete_batch($id) {
    global $DB, $USER;
    $success = $DB->delete_records('raomanager_batches', array('id'=>$id));
    return $success;
}

function assign_batch($users) {
    return FALSE;
}