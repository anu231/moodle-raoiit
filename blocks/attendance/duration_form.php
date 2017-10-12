<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
 
class attendance_duration_form extends moodleform {
    //Add elements to form
    function definition(){
        $mform =& $this->_form;
        $date_option = array(
            'startyear'=>2017,
            'stopyear'=>2020
            );
        $mform->addElement('date_selector','startdate','From Date',$date_option);
        $mform->addElement('date_selector','enddate','To Date',$date_option);
        //$this->add_action_buttons();
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Get Biometric Records");
        //$buttonarray[] = $mform->createElement('reset', 'resetbutton', get_string('revert'));
        //$buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}