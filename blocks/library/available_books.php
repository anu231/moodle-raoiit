<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;


$output = $PAGE->get_renderer('block_library');
$renderable = new view_available_books();

echo $output->header();
$PAGE->set_url('/blocks/library/available_books.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Available books');

echo $output->render($renderable);
echo $output->footer();
