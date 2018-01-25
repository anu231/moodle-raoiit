<?php

require_once('../../config.php');
require_once('renderer.php');
//require_once('locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;
$PAGE->set_url('/blocks/idcard_tracker/view_student_idcard.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Student IDCARDS');

$output = $PAGE->get_renderer('block_idcard_tracker');
//$select_idcard = required_param('select_idcard',PARAM_INT);
$select_idcard = optional_param('select_idcard', array(), PARAM_INT);
var_dump($select_idcard);
echo $output->header();
/*
foreach ($select_idcard as $new_id){
   echo  $new_id;
}
*/
$renderable = new view_student_idcard($select_idcard);
echo $output->render($renderable);
echo $output->footer();