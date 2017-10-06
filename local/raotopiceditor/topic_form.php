<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");

require_once($CFG->dirroot.'/local/raotopiceditor/locallib.php');

class raotopiceditor_booklet_form extends moodleform {

    function definition(){
        $mform =& $this->_form;
        $mform->addElement('hidden','topic',$this->_customdata['topic']);
        $mform->addElement('text','name','');
    }
}