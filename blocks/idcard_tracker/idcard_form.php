<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once('../branchadmin/locallib.php');
require_once("{$CFG->libdir}/raolib.php");
//require_once('locallib.php');

class add_idcard_form extends moodleform {
    function definition(){
        $maxbytes=60;
        $mform =& $this->_form;
        $mform->addElement('text', 'student_username','Student Roll Number','maxlength="6"');
        $mform->setType('student_username', PARAM_TEXT);
        //$mform->addElement('filepicker', 'profile_pic', get_string('profile_pic', 'block_idcard_tracker'), null, array('accepted_types' => '*'));
        $mform->addElement('file', 'profile_pic', get_string('profile_pic', 'block_idcard_tracker'), null, array('maxbytes' => $maxbytes, 'accepted_types' => '*'));
        $mform->setType('profile_pic', PARAM_RAW);
        $status_options = array('1' => 'Available');
        $select = $mform->addElement('select', 'idcard_status', get_string('idcard_status', 'block_idcard_tracker'), $status_options);
        $select->setSelected('1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit ID Card");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
        
    }
    
    function validation($data, $files){
        $errors = array();
        global $DB, $USER;
        $user = $DB->get_records('user', array('username'=>$data['student_username']));
        if(empty($user)){
            $errors['student_username'] = 'Student Not created in Edumate';
        }
        $student_user = $DB->get_record('user',array('username'=>$data['student_username']));
        profile_load_data($student_user);
        if ($student_user->profile_field_studycenter!=get_user_center()){
            $errors['student_username'] = 'Student does not belong to your center';
        }
        $size = getimagesize($data['profile_pic']);
        if ($size[0] > 50){
            $errors['profile_pic'] = 'Image is not valid';
        }
        return $errors;

    }
    
   
}
class view_profile_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $profile=$this->_customdata['profile_pic'];
        $mform->addElement('html', "<img src='$profile' style='width:100px; height:100px; margin-left:50px' />");
        $mform->addElement('text', 'student_username','Student Roll Number');
        $mform->setType('student_username', PARAM_INT);
        $mform->setDefault('student_username',$this->_customdata['student_username']);
      
        $mform->addElement('text', 'student_fullname','Student Fullname');
        $mform->setType('student_fullname', PARAM_TEXT);
        $mform->setDefault('student_fullname',$this->_customdata['student_fullname']);

        $mform->addElement('text', 'branch','Student Center');
        $mform->setType('branch', PARAM_TEXT);
        $mform->setDefault('branch',$this->_customdata['branch']);

        $mform->addElement('text', 'student_course','Student Course');
        $mform->setType('student_course', PARAM_TEXT);
        $mform->setDefault('student_course',$this->_customdata['student_course']);

        $mform->addElement('text', 'student_targetyear','Student Target Year');
        $mform->setType('student_targetyear', PARAM_INT);
        $mform->setDefault('student_targetyear',$this->_customdata['student_targetyear']);

        $mform->addElement('text', 'student_mobile_number','Student Mobile Number');
        $mform->setType('student_mobile_number', PARAM_INT);
        $mform->setDefault('student_mobile_number',$this->_customdata['student_mobile_number']);


        $mform->addElement('text', 'idcard_valid','ID Card Valid Date');
        $mform->setType('idcard_valid', PARAM_TEXT);
        $mform->setDefault('idcard_valid',$this->_customdata['idcard_valid']);

        $status_options = array('1' => 'APPROVE', '0' => 'REJECT');
        $select = $mform->addElement('select', 'idcard_status', 'APPROVE / REJECT', $status_options);
        $select->setSelected('1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit ID card");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
 
    function validation($data, $files){
        $errors = array();
        global $DB, $USER;
        $user = $DB->get_records('student_idcard_submit', array('student_username'=>$this->_customdata['student_username']));
        if(empty(!$user)){
            $errors['student_username'] = 'Student ID Card already generated';
        }
        return $errors;
    }
    
   
}        