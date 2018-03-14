<?php

require_once('../../config.php');
require_login();
require_once('renderer.php');

global $DB, $USER, $CFG, $PAGE;

$output = $PAGE->get_renderer('block_idcard_tracker');

echo $output->header();
$view_id = required_param('view_id',PARAM_INT);
$PAGE->set_url('/blocks/idcard_tracker/view_sent_idcards.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Sent ID Cards');
$renderable = new view_sent_idcards($view_id);
echo $output->render($renderable);
echo $output->footer();
