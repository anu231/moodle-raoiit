<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once('renderer.php');
require_login();
global $DB, $USER, $CFG, $PAGE;


$PAGE->set_url('/blocks/branchadmin/view_students.php', array('id' => $COURSE->id));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Branch Students');

$output = $PAGE->get_renderer('block_branchadmin');
$renderable = new view_students();

echo $output->header();
echo $output->render($renderable);
echo $output->footer();
