<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once("{$CFG->libdir}/raolib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');


class notification_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        //get courses
        $courses = convert_std_to_array(get_courses(),'shortname');
        $course_select = $mform->addElement('select','course','Select Course',$courses);
        $course_select->setMultiple(true);
        $mform->addElement('checkbox', 'sms', "Send SMS");
        $mform->addElement('checkbox', 'email', "Send Email");
        $notification_content = $mform->addElement('text', 'from','From Email(Valid Only for Email Notification)');
        $notification_content = $mform->addElement('text', 'subject','Subject(Valid Only for Email Notification)');
        $notification_content = $mform->addElement('textarea', 'notification','Notification','wrap="virtual" rows="20" cols="50"');
        $this->add_action_buttons($cancel=True, $submitlabel='Send Notification');
    }
}