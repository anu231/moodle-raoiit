<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once('renderer.php');
require_login();
global $DB, $USER, $CFG, $PAGE;


$PAGE->set_url('/blocks/branchadmin/view_student.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Student Profile');

$user_id = required_param('userid',PARAM_INT);

$output = $PAGE->get_renderer('block_branchadmin');
$renderable = new view_student($user_id);

echo $output->header();
echo $output->render($renderable);
echo $output->footer();
