<?php

require_once('../../config.php');
require_once('renderer.php');
//require_once('locallib.php');
require_login();
$PAGE->set_url('/blocks/idcard_tracker/view_student_idcard.php');
//$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Student IDCARDS');
$output = $PAGE->get_renderer('block_idcard_tracker');

global $DB, $USER, $CFG, $PAGE;

//echo $output->header();

$idcard_id = required_param('idcard_id',PARAM_INT);

$renderable = new view_student_idcard($idcard_id);
echo $output->render($renderable);
//echo $output->footer();