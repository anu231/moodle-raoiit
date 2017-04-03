<?php

// This will be the managers panel with links to all important pages

require_once('../../config.php');
require_login();
// TODO add capability checks
global $PAGE, $CFG;
$PAGE->set_url('/local/raomanager/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Raomanager');



require_login();
echo $OUTPUT->header();
echo '<a class="btn btn-primary" href="batch/index.php">Batches</a><br>';
echo '<hr>';
echo '<a class="btn btn-primary" href="notification/index.php">Notifications</a>';
echo '<hr>';
echo '<a class="btn btn-primary" href="admin/index.php">Manage Admins</a>';
echo $OUTPUT->footer();