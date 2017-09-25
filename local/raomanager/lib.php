<?php

// Single source of pluginmap
function local_raomanager_pluginmap(){
    return array(
            1 => 'Paper',
            2 => 'ReadyToHelp',
            3 => 'RaoManager::Admin',
            4 => 'RaoManager::Batch',
            5 => 'RaoManager::Course',
            6 => 'RaoManager::Notification',
        );
}

function user_has_access($username)
{
    $access_plugins = array();
    global $DB;$record = $DB->get_records('raomanager_admins',array('username' => $username));
    if (!$record){
        return False;
    } else {
        foreach($record as $rec){
            array_push($access_plugins,$rec->pluginname);
        }
        return $access_plugins;
    }
} 

function local_raomanager_extend_navigation(global_navigation $nav){
    global $USER;
    if ($USER->id==0){
        return;
    }
    $access_plugins = user_has_access($USER->username);
    $isadmin = is_siteadmin();
    if ($access_plugins == False && !$isadmin){
        return;
    } else {
        $previewnode = $nav->add('RaoManager', new moodle_url('/local/raomanager/index.php'), navigation_node::TYPE_CONTAINER);
        if ($isadmin || array_search(local_raomanager_pluginmap()[4],$access_plugins)){
            $batchnode = $previewnode->add("Add/Edit Batch", new moodle_url('/local/raomanager/batch/index.php'));
            $batchnode->make_active();
            $centernode = $previewnode->add("Add/Edit Centers", new moodle_url('/local/raomanager/center/index.php'));
            $centernode->make_active();
        }
        if ($isadmin || array_search(local_raomanager_pluginmap()[6],$access_plugins)){
            $notificationnode = $previewnode->add("Send Notifications", new moodle_url('/local/raomanager/notification/index.php'));
            $notificationnode->make_active();
        }
        if($isadmin) {
            $raoadminnode = $previewnode->add("Manage Raoadmins", new moodle_url('/local/raomanager/admin/index.php'));
            $raoadminnode->make_active();
        }
    }
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
