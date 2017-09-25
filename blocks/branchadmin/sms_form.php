<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->libdir}/raolib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
 
class branchadmin_batch_select_form extends moodleform {
    //Add elements to form
    function definition(){
        $mform =& $this->_form;
        $batches = convert_std_to_array(get_batches_for_user());
        $batch_select = $mform->addElement('select','batch','Select Batch',$batches);
        //$batch_select->setMultiple(true);
        //$mform->addElement('textarea', 'usernames', "Roll Numbers to Message(Please provide comma separated roll numbers)", 'wrap="virtual" rows="20" cols="50"');
        //$mform->addElement('textarea', 'message', "SMS Content", 'wrap="virtual" rows="20" cols="50"');
        //$mform->addRule('message',null,'required',null,'client');
        $this->add_action_buttons($cancel=False, $submitlabel='Show Students');
    }

    function validation($data, $files){
        $errors = array();
        //check atleast the batches or roll numbers need to be provided
        //if ($data['batches']=='' && $data['usernames']==''){
        if ($data['batches'] == ''){
            $errors['batches'] = 'Atleast batch or roll numbers need to be provided';
            //$errors['usernames'] = 'Atleast batch or roll numbers need to be provided';
        }
        /*else if ($data['batches']!='' && $data['usernames']!=''){
            $errors['batches'] = 'Both batch and roll numbers can\'t be provided';
            $errors['usernames'] = 'Both batch and roll numbers can\'t to be provided';
        }
        else if($data['usernames']!=''){
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
        }*/
        return $errors;
    }
}

class branchadmin_batch_students_form extends moodleform {

    function definition(){
        $mform =& $this->_form;
        $mform->addElement('checkbox','sendtoall','Send SMS to all Students');
        $students = convert_std_to_array(get_students_by_batch($this->_customdata['batch']));
        $student_select = $mform->addElement('select','students','Select Students',$students);
        $student_select->setMultiple(true);
        $mform->addElement('textarea', 'sms', "SMS Text", 'wrap="virtual" rows="20" cols="50"');
        $mform->setType('sms',PARAM_TEXT);
        $mform->addElement('hidden','batch',$this->_customdata['batch']);
        $mform->setType('batch',PARAM_INT);
        $this->add_action_buttons($submitlabel='Send SMS to selected Students');
    }

    function validation($data, $files){
        $errors = array();
        $valid = FALSE;
        if (array_key_exists('students', $data) && $data['students']!=''){
            $valid = TRUE;
        } else if (array_key_exists('sendtoall', $data) && $data['sendtoall']=='1'){
            $valid = TRUE;
        }
        if (!$valid){
            $errors['students'] = "Please Select at least one student or Select Checkbox to send to all";
        }
        if ($data['sms'] == ''){
            $errors['sms'] = "Please provide sms text";
        }
    }
}