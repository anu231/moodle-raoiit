<?php

// Single source of pluginmap
function local_raomanager_pluginmap(){
    return array(
            1 => 'Paper',
            2 => 'ReadyToHelp',
            3 => 'GrievancePortal',
            4 => 'RaoManager::Admin',
            5 => 'RaoManager::Batch',
            6 => 'RaoManager::Course',
            6 => 'RaoManager::Notification',
        );
}


function local_raomanager_extend_navigation(global_navigation $nav){

    $previewnode = $nav->add('RaoManager', new moodle_url('/local/raomanager/index.php'), navigation_node::TYPE_CONTAINER);
    $batchnode = $previewnode->add("Add/Edit Batch", new moodle_url('/local/raomanager/batch/index.php'));
    $batchnode->make_active();
    $notificationnode = $previewnode->add("Send Notifications", new moodle_url('/local/raomanager/notification/index.php'));
    $notificationnode->make_active();
    $raoadminnode = $previewnode->add("Manage Raoadmins", new moodle_url('/local/raomanager/admin/index.php'));
    if(is_siteadmin())
        $raoadminnode->make_active();

}

// Check if the user has permission to use a plugin
// return boolean. False for failure
function local_raomanager_has_permission($pluginname) {
    global $USER, $DB;
    if( is_siteadmin() )
        return TRUE;
    $record = $DB->get_record('raomanager_admins', array('username' => $USER->username, 'pluginname' => $pluginname ));
    if(!$record)
        return FALSE;
    else {
        if($record->pluginname == $pluginname)
            return TRUE;
        else
            return FALSE;
    }
}   
