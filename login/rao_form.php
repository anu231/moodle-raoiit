<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->dirroot.'/user/lib.php');

class parent_login_form extends moodleform {

    public function definition() {
        global $CFG;

        $mform = $this->_form;

        $mform->addElement('text', 'username', 'Roll No');
        $mform->setType('username', PARAM_RAW);
        $mform->addRule('username',"Roll Number required",'required','','client');

        $mform->addElement('text', 'mobile', 'Parent Mobile Number for OTP');
        $mform->setType('mobile', PARAM_TEXT);
        $mform->addRule('mobile',"Mobile Number required",'required','','client');
        $this->add_action_buttons(true, 'Send OTP');
    }

    function validation($data, $files){
        $errors = array();
        //check if the username is valid
        global $DB, $CFG;
        require_once($CFG->libdir.'/raolib.php');
        $user = null;
        
        $user = $DB->get_record('user',array('username'=>$data['username']));
        if ($user == null){
            $errors['username'] = 'Wrong Roll Number';
            return $errors;
        }
        $info_fields = array('fathermobile','mothermobile');
        $parent_nos = get_rao_user_profile_fields($info_fields, $user);
        if ($parent_nos['fathermobile'] != $data['mobile'] && $parent_nos['mothermobile'] != $data['mobile']){
            $errors['mobile'] = 'Mobile number is not registered as parent number for this student';
        }
        return $errors;
    }
}