<?php

require('../../config.php');
//require('../../lib/moodlelib.php');
global $CFG, $PAGE, $OUTPUT, $SESSION;

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_pagelayout('login');
$OUTPUT->header();
require('otpform.php');

echo $OUTPUT->footer();
