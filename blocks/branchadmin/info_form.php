<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_once("{$CFG->libdir}/raolib.php");
 
class branchadmin_info_form extends moodleform {
    //Add elements to form
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('text', 'username', "Roll Number of the Student");
        $mform->addRule('username',null,'required',null,'client');
        $this->add_action_buttons();
    }
}

class attendane_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('date_selector', 'date', 'Date');
        
        $options = convert_std_to_array(get_batches_for_user());
         $mform->addElement('select', 'batch', 'Select Batch', $options);
   
        $mform->addElement('text', 'scheduled_id', 'Scheduled Id','required');
        $mform->setType('scheduled_id', PARAM_INT);

        $mform->addElement('text', 'tpoic_id', 'Topic Id','required');
        $mform->setType('tpoic_id', PARAM_INT);
       
        $mform->addElement('textarea', 'roll_numbers', 'Roll Numbers', 'wrap="virtual" rows="10" cols="50",required');
        
        $options = array('0' => 'SMS NOT SENT','1' => 'SMS SENT');
        $select = $mform->addElement('select', 'sms_status','SMS Status', $options);
        $select->setSelected('0');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    /*
    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
      
        return $errors;
    }
    */
}