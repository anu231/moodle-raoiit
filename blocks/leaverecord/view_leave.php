<?php

require_once('../../config.php');
require_once('leaves_form.php');
require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
 $email = required_param('user',PARAM_TEXT);
 $from_date = required_param('from',PARAM_TEXT);
 $to_date = required_param('to',PARAM_TEXT);

$PAGE->set_url('/blocks/leaverecord/view_leave.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Apply Leaves');

$output = $PAGE->get_renderer('block_leaverecord');
$renderable = new view_apply_leaves();
echo $output->header();
echo $output->render($renderable);
echo $output->footer();
