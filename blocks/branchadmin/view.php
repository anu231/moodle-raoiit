<?php
defined('MOODLE_INTERNAL') || die();
require_once('../../config.php');
//require_once('simplehtml_form.php');
 
global $DB, $OUTPUT, $PAGE;
// Check for all required variables.
if ($data = $mform->get_data()) {
$ins = new stdClass();
$ins->name = $data->name;
$ins->email = $data->email;
$ins->id = $DB->insert_record('branchadmin', $ins);
return $this->$data;
}