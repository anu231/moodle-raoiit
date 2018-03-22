<?php

require_once('../../config.php');
require_once('renderer.php');
//require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/idcard_tracker/view_idcards.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View IDCARDS');
$PAGE->requires->js('/blocks/idcard_tracker/js/idcard.js');
$output       = $PAGE->get_renderer('block_idcard_tracker');
$rowcount     = $DB->count_records('student_idcard_submit');
$sort         = optional_param('sort', 'student_username', PARAM_ALPHA);
$dir          = optional_param('dir', 'ASC', PARAM_ALPHA);
$page         = optional_param('page', 0, PARAM_INT);
$perpage      = optional_param('perpage', 5, PARAM_INT);
$baseurl      = new moodle_url('/blocks/idcard_tracker/view_pending_idcards.php',array('perpage' => $perpage));
$renderable = new view_pending_idcards($page, $perpage);
echo $output->header();
echo $output->render($renderable);
echo $output->paging_bar($rowcount, $page, $perpage, $baseurl);
echo $output->footer();
