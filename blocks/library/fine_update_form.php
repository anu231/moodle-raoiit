<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
class fine_update_form extends moodleform {
    //Add elements to form
    function definition(){
        //dummy data //
        $student_username=918016;
        $bookid='CHMOD';
        $branch_issuer='admin';
        $amount='60';
        $return_date='2017-11-11';
        //dummy data //
        $mform =& $this->_form;
        $mform->addElement('text', 'student_username', get_string('student_username', 'block_library'));
        $mform->setDefault('student_username', $student_username);
        $mform->setType('student_username', PARAM_TEXT);

        $fine = array('0' => 'Paid','2' => 'Later');
        $mform->addElement('select', 'fine_status', get_string('fine_status', 'block_library'), $fine);
       
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Add Fine Status");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}