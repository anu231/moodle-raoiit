<?php

require_once('../../config.php');
require_once('renderer.php');

require_login();
global $PAGE, $CFG, $USER;
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/raotopiceditor/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Rao Topic Editor');

if ($USER->username != 'admin' || $USER->username != 'sahiladmin'){
    echo 'Access Denied';
    exit;
}

$output = $PAGE->get_renderer('local_raotopiceditor');
$renderable = new topics();

echo $output->header();
echo $output->render($renderable);
echo $output->footer();