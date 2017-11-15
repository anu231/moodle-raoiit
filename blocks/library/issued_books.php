<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;

$PAGE->set_url('/blocks/library/issued_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Issued books');

$output = $PAGE->get_renderer('block_library');
$renderable = new view_all_books();
echo $output->header();
echo $output->render($renderable);
echo $output->footer();
