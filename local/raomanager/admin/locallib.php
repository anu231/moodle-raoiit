<?php
// Assign new admin

// Single source of pluginmap
function rm_admin_pluginmap(){
    return array(
            0 => 'RaoManager',
            1 => 'Paper',
            2 => 'Ready To Help',
            3 => 'Grievance Portal'
        );
}

function rm_admin_add($permission, $mform = NULL) {
    global $DB, $USER;
    $permission->timecreated = time();
    $pluginmap = rm_admin_pluginmap();
    $permission->pluginname = $pluginmap[$permission->pluginid];
    if ($id = $DB->insert_record('raomanager_admins', $permission))
        return $id;
    else 
        return FALSE;
}

// edit
function rm_admin_edit($permission, $mform = NULL) {
    global $DB, $USER;
    $permission->id;
    $pluginmap = rm_admin_pluginmap();
    $permission->pluginname = $pluginmap[$permission->pluginid];
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
