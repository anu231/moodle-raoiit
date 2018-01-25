<?php

require_once('../../config.php');
require_once('renderer.php');
//require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/idcard_tracker/view_idcards.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View IDCARDS');
$output = $PAGE->get_renderer('block_idcard_tracker');
$renderable = new view_idcards();
echo $output->header();
echo $output->render($renderable);
echo $output->footer();
