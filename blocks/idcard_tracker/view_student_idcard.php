<?php

require_once('../../config.php');
require_once('renderer.php');
//require_once('locallib.php');
require_login();
$PAGE->set_url('/blocks/idcard_tracker/view_student_idcard.php');
//$PAGE->set_pagelayout('standard');
$PAGE->set_heading('View Student IDCARDS');
$output = $PAGE->get_renderer('block_idcard_tracker');

global $DB, $USER, $CFG, $PAGE;

echo $output->header();

if (isset($idcard_id)) {
    $idcard_id = optional_param('idcard_id', array(), PARAM_INT);
}
else{
    $select_idcard = optional_param('select_idcard', array(), PARAM_INT);
}




/*
foreach ($select_idcard as $new_id){
   echo  $new_id;
}
*/
$renderable = new view_student_idcard();
echo $output->render($renderable);
echo $output->footer();