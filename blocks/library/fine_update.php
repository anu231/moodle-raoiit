<?php

require_once('../../config.php');

require_once('authentication.php');
global $DB, $USER, $CFG, $PAGE;

require_login();
$PAGE->set_url('/blocks/library/fine_update.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Student Profile');

echo $amount = required_param('id',PARAM_INT);

$output = $PAGE->get_renderer('block_library');
$renderable = new view_fine_status($abc);

echo $output->header();echo $abc=$_GET['id'];
echo $output->render($renderable);
echo $output->footer();
