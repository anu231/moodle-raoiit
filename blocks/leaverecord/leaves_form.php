<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once('locallib.php');

class leave_application_form extends moodleform {
    function definition(){
$mform =& $this->_form;
$radioarray=array();
$radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('fullday', 'block_leaverecord'), 'F');
$radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('firsthalf', 'block_leaverecord'), 'H');
$radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('secondhalf', 'block_leaverecord'), 's');
$radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('multipledays', 'block_leaverecord'), 'md');
$mform->addGroup($radioarray, 'radioar', '', array(' '), false);
$mform->setDefault('leave_status', 'F');
$mform->addElement('date_selector', 'date', 'Apply Date');
$mform->addElement('date_selector', 'leave_from', get_string('from'),['class' => 'leave']);
$mform->addElement('date_selector', 'leave_to', get_string('to'),['class' => 'leave']);
$mform->addElement('textarea', 'leave_reason', 'Leave Reason', 'wrap="virtual" rows="10" cols="50",required');
$mform->setType('leave_reason', PARAM_RAW);
$buttonarray=array();
$buttonarray[] = $mform->createElement('submit', 'submit', "Apply leaves");
$buttonarray[] = $mform->createElement('cancel');
$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

    function validation($data, $files){
$errors = array();
global $DB, $USER,$CFG;

       
return $errors;
    }
}
//Leave for OD //

class od_application_form extends moodleform {
    function definition(){
$mform =& $this->_form;
$radioarray=array();
$radioarray[] = $mform->createElement('radio', 'od_status', '', get_string('fullday', 'block_leaverecord'), 0);
$radioarray[] = $mform->createElement('radio', 'od_status', '', get_string('multipledays', 'block_leaverecord'), 3);
$mform->addGroup($radioarray, 'radioar', '', array(' '), false);
$mform->setDefault('od_status', 0);


$mform->addElement('date_selector', 'od_date', 'Apply Date');
$mform->addElement('date_selector', 'od_date1', 'Apply Date');
//$mform->addElement('date_seldate_time_selectorector', 'od_from', get_string('from'),['class' => 'leave']);
//$mform->addElement('date_time_selector', 'od_to', get_string('to'),['class' => 'leave']);
$mform->addElement('header', 'odstarttime', 'OD Start Time');
$start_od_time_hour= array('01' => '01','02' => '02','03' => '03','04' => '04','05' => '05','06' => '06','07' => '07','08' => '08','09' => '09','10' => '10','11' => '11','12' => '12','13' => '13','14' => '14','15' => '15','16' => '16','17' => '17','18' => '18','19' => '19','20' => '20','21' => '21','22' => '22','23' => '23','24' => '24');
$start_od_time_min = array('00' => '00','01' => '01','02' => '02','03' => '03','04' => '04','05' => '05','06' => '06','07' => '07','08' => '08','09' => '09','10' => '10','11' => '11','12' => '12','13' => '13','14' => '14','15' => '15','16' => '16','17' => '17','18' => '18','19' => '19','20' => '20','21' => '21','22' => '22','23' => '23','24' => '24','25' => '25','26' => '26','27' => '27','28' => '28','29' => '29','30' => '30','31' => '31','32' => '32','33' => '33','34' => '34','35' => '35','36' => '36','37' => '37','38' => '38','39' => '39','40' => '40','41' => '41','42' => '42','43' => '43','44' => '44','45' => '45','46' => '46','47' => '47','48' => '48','49' => '49','50' => '50','51' => '51','52' => '52','53' => '53','54' => '54','55' => '55','56' => '56','57' => '57','58' => '58','59' => '59','60' => '60');
$mform->addElement('select', 'od_start_time_in_hour', 'Start OD Time in (Hours)', $start_od_time_hour);
$mform->addElement('select', 'od_start_time_in_min', 'Start OD Time in (Min)', $start_od_time_min);

$mform->addElement('header', 'odendtime', 'OD End Time');

$mform->addElement('select', 'od_end_time_in_hour', 'End OD Time in (Hours)', $start_od_time_hour);
$mform->addElement('select', 'od_end_time_in_min', 'End OD Time in (Min)', $start_od_time_min);

$mform->addElement('date_selector', 'od_start_from', 'Apply Date');
$mform->addElement('date_selector', 'od_end_to', 'Apply Date');

$mform->addElement('header', 'odnotes', 'OD Notes');
$mform->addElement('textarea', 'od_reason', 'OD Reason', 'wrap="virtual" rows="10" cols="50",required');
$mform->setType('od_reason', PARAM_RAW);


$buttonarray=array();
$buttonarray[] = $mform->createElement('submit', 'submit', "Apply OD");
$buttonarray[] = $mform->createElement('cancel');
$mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

    function validation($data, $files){
$errors = array();
global $DB, $USER,$CFG;

       
return $errors;
    }
}

// View Leave Form //

class view_leave_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('date_selector', 'from', 'From Date');
        $mform->addElement('date_selector', 'to', 'To Date');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

    }
}    
// View Leave Form //

// Attendance Master //

class attendance_master_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('date_selector', 'from', 'From Date');
        $mform->addElement('date_selector', 'to', 'To Date');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

    }
}    
// Attendance Master //


// Edit Faculty Register //
class edit_faculty_register extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $lec_cancelled=array();
        $lec_cancelled[] = $mform->createElement('radio', 'lecture_cancelled', 'sac','Yes', 1);
        $lec_cancelled[] = $mform->createElement('radio', 'lecture_cancelled', 'asc', 'No', 0);
        $mform->addGroup($lec_cancelled, 'lecture_cancelled', '', array(' '), false);
        $mform->setDefault('lecture_cancelled', 0);


        $topic = array('0' => 'Electro Chemistry','1' => 'D Block');
        $mform->addElement('select', 'topic', 'Select Topic', $topic);
        $mform->addElement('text', 'lec_no', 'Lecture No','required');
        $mform->setDefault('lec_no',1);
        $mform->addElement('checkbox', 'mark_topic_completion', 'Mark Topic Completion?');
        $mform->addElement('header', 'hw_section', 'HomeWork Section');
        $hw_status=array();
        $hw_status[] = $mform->createElement('radio', 'hw_status', 'sac','Yes', 1);
        $hw_status[] = $mform->createElement('radio', 'hw_status', 'asc', 'No', 0);
        $mform->addGroup($hw_status, 'hw_status', '', array(' '), false);
        $mform->setDefault('hw_status', 0);
        $mform->addElement('textarea', 'hw_content', 'HomeWork Content', 'wrap="virtual" rows="10" cols="50",required');
        $mform->setType('hw_content', PARAM_RAW);
        $mform->addElement('header', 'br_section', 'Break Given Section');
        $break_given=array();
        $break_given[] = $mform->createElement('radio', 'break_given', 'sac','Yes', 1);
        $break_given[] = $mform->createElement('radio', 'break_given', 'asc', 'No', 0);
        $mform->addGroup($break_given, 'break_given', '', array(' '), false);
        $mform->setDefault('break_given', 0);
        $mform->addElement('text', 'break_duration', 'Break Duration','required');
        $mform->setDefault('break_duration',0);
        $mform->addElement('header', 'note_section', 'Note');
        $mform->addElement('textarea', 'note', 'Note Content', 'wrap="virtual" rows="10" cols="50",required');
        $mform->setType('note', PARAM_RAW);
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);

    }
}    
// Edit Faculty Register //