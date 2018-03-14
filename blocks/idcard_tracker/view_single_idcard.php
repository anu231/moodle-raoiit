<?php

require_once('../../config.php');
require_once('renderer.php');

require_login();
global $DB, $USER, $CFG, $PAGE;

$output = $PAGE->get_renderer('block_idcard_tracker');

$single_id = required_param('single_id',PARAM_INT);
//echo $output->header();

$PAGE->set_url('/blocks/idcard_tracker/view_single_idcard.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View ID Card');
$renderable = new view_single_idcard($single_id);
echo $output->render($renderable);
//echo $output->footer();
