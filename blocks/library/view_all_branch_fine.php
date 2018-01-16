<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/library/view_all_branch_fine.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Available books');
$output = $PAGE->get_renderer('block_library');
$renderable = new view_all_rao_branch_fine();

echo $output->header();


echo $output->render($renderable);
echo $output->footer();
