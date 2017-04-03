<?php

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
