<?php

// This will be the managers panel with links to all important pages

require_once('../../config.php');

global $PAGE, $CFG;
$PAGE->set_url('/local/raowebservice/index.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('RaoWebservice');



echo $OUTPUT->header();
echo '<a class="btn btn-primary" href="#">Rao Web Service </a><br>';
echo $OUTPUT->footer();