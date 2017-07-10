<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
 
class branchadmin_sms_form extends moodleform {
    //Add elements to form
    function definition(){
        $mform =& $this->_form;
        $batches = get_batches_for_user(get_user_center());
        $batch_select = $mform->addElement('select','batches','Select Batches',$batches);
        $batch_select->setMultiple(true);
        $mform->addElement('textarea', 'usernames', "Roll Numbers to Message(Please provide comma separated roll numbers)", 'wrap="virtual" rows="20" cols="50"');
        $mform->addElement('textarea', 'message', "SMS Content", 'wrap="virtual" rows="20" cols="50"');
        $mform->addRule('message',null,'required',null,'client');
        $this->add_action_buttons();
    }

    function validation($data, $files){
        $errors = array();
        //check atleast the batches or roll numbers need to be provided
        if ($data['batches']=='' && $data['usernames']==''){
            $errors['batches'] = 'Atleast batch or roll numbers need to be provided';
            $errors['usernames'] = 'Atleast batch or roll numbers need to be provided';
        }
        else if ($data['batches']!='' && $data['usernames']!=''){
            $errors['batches'] = 'Both batch and roll numbers can\'t be provided';
            $errors['usernames'] = 'Both batch and roll numbers can\'t to be provided';
        } else if($data['usernames']!=''){
            //check whether the roll numers are 6 digit numbers
            $rolls = explode(',',$data['usernames']);
            foreach($rolls as $roll){
                if (!is_numeric($roll)){
                    $errors['usernames'] = 'Please provide only student roll numbers';
                    break;
                }else if (strlen($roll)!=6){
                    $errors['usernames'] = 'Please provide valid 6 digit roll numbers';
                    break;
                }
            }
        }
        return $errors;
    }
}