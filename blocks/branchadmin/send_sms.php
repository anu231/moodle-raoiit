<?php

require_once('../../config.php');
//require_once('classes/output/view_students.php');
require_once('sms_form.php');

require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_login();
global $DB, $USER, $CFG, $PAGE;

$PAGE->set_url('/blocks/branchadmin/send_sms.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Send SMS to students');

$output = $PAGE->get_renderer('block_branchadmin');

$branchadmin_sms_form = new branchadmin_sms_form();
 


echo $output->header();
     $branchadmin_sms_form->display();
echo $output->footer();