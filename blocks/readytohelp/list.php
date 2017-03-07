<?php
/**
 * Lists all the grievances raised by the user in a tabular format
 */

require_once('../../config.php');
require_once('locallib.php');


require_login();

global $OUTPUT, $PAGE, $USER;
$PAGE->set_url('/blocks/readytohelp/view.php', array('uname' => $USER->username));
$PAGE->set_pagelayout('standard');
$PAGE->set_heading(get_string('grievance_list', 'block_readytohelp'));

//get the list of grievances

$output = $PAGE->get_renderer('block_readytohelp');

echo $output->header();
echo $output->grievance_list($USER->username);
echo $output->footer();
