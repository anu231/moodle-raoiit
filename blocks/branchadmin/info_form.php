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

class attendance_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $mform->addElement('date_selector', 'date', 'Date');
        
        $options = convert_std_to_array(get_batches_for_user());
        $mform->addElement('select', 'batch', 'Select Batch', $options);
   
        $mform->addElement('select', 'schedule_id', 'Select Lecture',array());
        //$mform->setType('schedule_id', PARAM_INT);

        //$mform->addElement('text', 'topic_id', 'Topic Id','required');
        //$mform->setType('topic_id', PARAM_INT);
    
        $mform->addElement('textarea', 'roll_numbers', '', 'wrap="virtual" rows="1" cols="50" style="visibility:hidden",required',array('style'=>'visibility:hidden;'));
        //$mform->addElement('button', 'finalize_select_student', 'Finalize Absent Students', array('onclick'=>'finalize_students()'));
        $roll_select = $mform->addElement('static','select_roll_numbers', 'Select Absent Students','');
        //$options = array('0' => 'SMS NOT SENT','1' => 'SMS SENT');
        //$select = $mform->addElement('select', 'sms_status','SMS Status', $options);
        //$select->setSelected('0');
        $buttonarray=array();
        //$buttonarray[] = $mform->createElement('submit', 'submit', "Submit");
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit",array('onclick'=>'finalize_students()'));
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    
    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        //verify that all users belong to the branch admin's center
        $user_center = get_user_center();
        $usql = $data['roll_numbers'];
        //list($usql, $params) = $DB->get_in_or_equal($data->roll_numbers);
        $sql = <<<EOT
        select u.id, ud.data from {user} as u join {user_info_data} as ud join {user_info_field} as ufi on u.id = ud.userid and ufi.id = ud.fieldid
        where u.id in ($usql) and ufi.shortname='center'
EOT;
        $res = $DB->get_records_sql($sql,array());
        foreach($res as $rec){
            if ($rec->data != $user_center){
                $errors['roll_numbers'] = 'One of the users does not belong to your center';
                break;
            }
        }
        return $errors;
    }
}