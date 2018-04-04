<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once('locallib.php');
require_once('renderer.php');
require_login();
global $DB, $USER, $CFG, $PAGE;


$PAGE->set_url('/blocks/branchadmin/student_batchwise.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Batch Students');

$output = $PAGE->get_renderer('block_branchadmin');
$batchid = required_param('id', PARAM_INT);

echo $output->header();
echo '<h4>'.get_batch_name($batchid).'</h4>';
$renderable = new batch_students($batchid);
echo $output->render($renderable);
echo $output->footer();