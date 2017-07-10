<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
 
class branchadmin_info_form extends moodleform {
    //Add elements to form
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('text', 'username', "Roll Number of the Student");
        $mform->addRule('username',null,'required',null,'client');
        $this->add_action_buttons();
    }
}