<?php
// Assign new admin
require_once('../lib.php');


function rm_admin_add($permission, $mform = NULL) {
    global $DB, $USER;
    $permission->timecreated = time();
    $pluginmap = local_raomanager_pluginmap();
    $permission->pluginname = $pluginmap[$permission->pluginname];
    if ($id = $DB->insert_record('raomanager_admins', $permission))
        return $id;
    else 
        return FALSE;
}

// edit
function rm_admin_edit($permission, $mform = NULL) {
    global $DB, $USER;
    $permission->id;
    $pluginmap = local_raomanager_pluginmap();
    $permission->pluginname = $pluginmap[$permission->pluginname];
    $permission->timemodified = time();
    if ($success = $DB->update_record('raomanager_admins', $permission))
        return $success;
    else 
        return FALSE;
}

// Delete
function rm_admin_delete($id) {
    global $DB, $USER;
    $success = $DB->delete_records('raomanager_admins', array('id'=>$id));
    return $success;
}
