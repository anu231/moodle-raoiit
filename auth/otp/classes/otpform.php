<?php

defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");

class auth_otp_otpform extends moodleform {
    function definition(){
        $mform =& $this->_form; 
        $mform->addElement('text','password','OTP',array('size'=>'10'));
        // hidden elements
        $mform->addElement('hidden', 'username');
        $mform->addElement('static','message','Message','');
        $this->add_action_buttons(false,'Submit');
    }
}
