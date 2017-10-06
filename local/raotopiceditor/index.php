<?php

require_once('../../config.php');
require_once('renderer.php');

global $PAGE, $CFG;
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/raotopiceditor/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Rao Topic Editor');

$output = $PAGE->get_renderer('local_raotopiceditor');
$renderable = new topics();

echo $output->header();
echo $output->render($renderable);
echo $output->footer();