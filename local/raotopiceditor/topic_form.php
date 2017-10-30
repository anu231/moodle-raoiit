<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");

require_once($CFG->dirroot.'/local/raotopiceditor/locallib.php');
require_once($CFG->dirroot.'/mod/raobooklet/locallib.php');

class raotopiceditor_booklet_form extends moodleform {

    function definition(){
        $mform =& $this->_form;
        $mform->addElement('hidden','id','0');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden','topic',$this->_customdata['topic']);
        $mform->setType('topic', PARAM_INT);
        $mform->addElement('hidden','type',$this->_customdata['type']);
        $mform->setType('type', PARAM_TEXT);
        $mform->addElement('text','name','Name for the Booklet',array('size'=>'40'));
        $mform->setType('name', PARAM_TEXT);
        $booklets = list_booklets_select();
        $mform->addElement('select', 'value','Select Booklet',$booklets);
        $mform->setType('value', PARAM_INT);
        //$mform->addElement('text','value',array('size'=>'40'));
        $this->add_action_buttons($cancel=False, $submitlabel='Save Booklet Entry');
    }
}

class raotopiceditor_video_form extends moodleform {

    function definition(){
        $mform =& $this->_form;
        $mform->addElement('hidden','id','0');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden','topic',$this->_customdata['topic']);
        $mform->setType('topic', PARAM_INT);
        $mform->addElement('hidden','type',$this->_customdata['type']);
        $mform->setType('type', PARAM_TEXT);
        $mform->addElement('text','name','Name for the Video',array('size'=>'40'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addElement('text', 'value','Video URL',array('size'=>'40'));
        $mform->setType('value', PARAM_TEXT);
        //$mform->addElement('text','value',array('size'=>'40'));
        $this->add_action_buttons($cancel=False, $submitlabel='Save Video Entry');
    }
}

class raotopiceditor_link_form extends moodleform {

    function definition(){
        $mform =& $this->_form;
        $mform->addElement('hidden','id','0');
        $mform->setType('id', PARAM_INT);
        $mform->addElement('hidden','topic',$this->_customdata['topic']);
        $mform->setType('topic', PARAM_INT);
        $mform->addElement('hidden','type',$this->_customdata['type']);
        $mform->setType('type', PARAM_TEXT);
        $mform->addElement('text','name','Name for the Link',array('size'=>'40'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addElement('text', 'value','Link URL',array('size'=>'40'));
        $mform->setType('value', PARAM_TEXT);
        //$mform->addElement('text','value',array('size'=>'40'));
        $this->add_action_buttons($cancel=False, $submitlabel='Save Link Entry');
    }
}