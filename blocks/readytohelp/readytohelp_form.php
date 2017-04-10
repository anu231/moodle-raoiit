<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/readytohelp/locallib.php');

class readytohelp_create_form extends moodleform{
    function definition(){
        $mform =& $this->_form; 
        $mform->addElement('select','category','Category',get_grievance_categories());
        $mform->addRule('category','Category can\'t be empty','required',null,'client');
        
        $mform->addElement('text','subject','Subject',array('size'=>'40'));
        $mform->setType('subject', PARAM_NOTAGS);        
        $mform->addRule('subject','Subject can\'t be empty','required',null,'client');

        $mform->addElement('textarea','description','Description',array('rows'=>'5','cols'=>'40'));
        $mform->setType('description', PARAM_NOTAGS);
        $mform->addRule('description','Description can\'t be empty','required',null,'client');
        // hidden elements
        $mform->addElement('hidden', 'blockid');
        $mform->setType('blockid', PARAM_INT);
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $this->add_action_buttons();
    }   
}

class readytohelp_reply_form extends moodleform{
    function definition(){
        $mform =& $this->_form;


        $mform->addElement('textarea', 'body', 'Your Response: ', 'rows="5" cols="50"');
        $mform->setType('body', PARAM_NOTAGS);
        $mform->addRule('body','Response can\'t be empty','required',null,'client');
        $mform->setDefault('body', isset($this->_customdata['body']) ? $this->_customdata['body'] : "" );

        // Hidden fields

        // URL param
        $mform->addElement('hidden', 'grievance_id', '');
        $mform->setType('grievance_id', PARAM_RAW);
        $mform->setDefault('grievance_id',$this->_customdata['grievance_id']);

        // URL param
        $mform->addElement('hidden', 'deptid', '');
        $mform->setType('deptid', PARAM_INT);
        $mform->setDefault('deptid',$this->_customdata['deptid']);

        // URL param
        $mform->addElement('hidden', 'email', '');
        $mform->setType('email', PARAM_RAW);
        $mform->setDefault('email',$this->_customdata['email']);

        $this->add_action_buttons();
    }
}

class readytohelp_department_form extends moodleform{
    function definition(){
        $mform =& $this->_form;

        $mform->addElement('header', 'header1', 'Manage departments for this grievance');

        $select = $mform->addElement('select','departments','Select Departments (Ctrl+click to select multiple) ',get_grievance_departments(), 'style="height:9em;"');
        $select->setMultiple(true);
        $mform->addRule('departments','At least one department must be assigned','required',null,'client');

        // hidden elements
        $mform->addElement('hidden', 'courseid');
        $mform->setType('courseid', PARAM_INT);
        $this->add_action_buttons();
    }   
}
