<?php
// Add a new batch
function rm_batch_add($batch, $mform = NULL) {
    global $DB, $USER;
    $batch->timecreated = time();
    $batch->assignee = $USER->username;
    if ($id = $DB->insert_record('raomanager_batches', $batch))
        return $id;
    else 
        return FALSE;
}

// edit an existing batch
function rm_batch_edit($batch, $mform = NULL) {
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
function rm_batch_delete($id) {
    global $DB, $USER;
    $success = $DB->delete_records('raomanager_batches', array('id'=>$id));
    return $success;
}
