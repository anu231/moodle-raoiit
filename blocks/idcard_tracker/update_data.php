<?php
require_once('../../config.php');
require_login();
 $button_status=$_POST['button_status'];
 $id=$_POST['id'];
//update query
global $DB,$USER;
$button_status = required_param('button_status',PARAM_TEXT);
$id = required_param('id',PARAM_TEXT);
$idcard_record = $DB->update_record('student_idcard_submit', array("id"=>$id,"idcard_status"=>$button_status));
$idcard_record_data=json_encode($idcard_record);
$idcard_record_data;
?>