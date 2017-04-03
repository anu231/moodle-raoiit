<?php

// This will be the managers panel with links to all important pages

require_once('../../config.php');
require_once('lib.php');
global $PAGE, $CFG;
$PAGE->set_url('/local/raomanager/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Raomanager');

require_login();
if(! local_raomanager_has_permission('RaoManager::Admin') )
    redirect(new moodle_url('/'));

echo $OUTPUT->header();
echo '<a class="btn btn-primary" href="batch/index.php">Manage Batches</a><br>';
echo '<hr>';
echo '<a class="btn btn-primary" href="notification/index.php">Manage Notifications</a>';
echo '<hr>';
echo '<a class="btn btn-primary" href="admin/index.php">Manage Admins</a>';
echo $OUTPUT->footer();