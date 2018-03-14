<?php

require_once('../../config.php');
require_login();
require_once('renderer.php');

global $DB, $USER, $CFG, $PAGE;

$output = $PAGE->get_renderer('block_idcard_tracker');

$multiple_id = required_param('multiple_id',PARAM_INT);
//echo $output->header();
$PAGE->set_url('/blocks/idcard_tracker/view_multiple_idcards.php');
//$PAGE->set_pagelayout('standard');
//$PAGE->set_heading('View Multiple Cards');
$renderable = new view_multiple_idcards($multiple_id);
echo $output->render($renderable);
//echo $output->footer();
