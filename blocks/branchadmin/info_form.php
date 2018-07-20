<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');
require_once("{$CFG->libdir}/raolib.php");
require_once('locallib.php');
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

class request_timetable extends moodleform {
    function definition(){
        $mform =& $this->_form;
        $batch = array("TPJ1","PP_10");
        $mform->addElement('select', 'batch', 'Batch', $batch);
        $subject = array("Physics","Chemistry","Mathematics","Biology");
        $mform->addElement('select', 'subject', 'Select Subject', $subject);
        $topic = array("Newton's Laws of Motion","Vectors and Projectiles","Atomic Structure","Equations and Stoichiometry");
        $mform->addElement('select', 'topic', 'Select Topic', $topic);
        $mform->addElement('date_selector', 'request_date', 'Request Date');
        $mform->addElement('header', 'lecture_start_time', 'Lecture Start Time');
        $start_lec_time_hour= array('01' => '01','02' => '02','03' => '03','04' => '04','05' => '05','06' => '06','07' => '07','08' => '08','09' => '09','10' => '10','11' => '11','12' => '12','13' => '13','14' => '14','15' => '15','16' => '16','17' => '17','18' => '18','19' => '19','20' => '20','21' => '21','22' => '22','23' => '23','24' => '24');
        $start_lec_time_min = array('00' => '00','01' => '01','02' => '02','03' => '03','04' => '04','05' => '05','06' => '06','07' => '07','08' => '08','09' => '09','10' => '10','11' => '11','12' => '12','13' => '13','14' => '14','15' => '15','16' => '16','17' => '17','18' => '18','19' => '19','20' => '20','21' => '21','22' => '22','23' => '23','24' => '24','25' => '25','26' => '26','27' => '27','28' => '28','29' => '29','30' => '30','31' => '31','32' => '32','33' => '33','34' => '34','35' => '35','36' => '36','37' => '37','38' => '38','39' => '39','40' => '40','41' => '41','42' => '42','43' => '43','44' => '44','45' => '45','46' => '46','47' => '47','48' => '48','49' => '49','50' => '50','51' => '51','52' => '52','53' => '53','54' => '54','55' => '55','56' => '56','57' => '57','58' => '58','59' => '59','60' => '60');
        $mform->addElement('select', 'lec_start_time_in_hour', 'Start Lec Time in (Hours)', $start_lec_time_hour);
        $mform->addElement('select', 'lec_start_time_in_min', 'Start Lec Time in (Min)', $start_lec_time_min);
        $mform->addElement('header', 'lecture_end_time', 'Lecture End Time');
        $mform->addElement('select', 'lec_end_time_in_hour', 'End Lec Time in (Hours)', $start_lec_time_hour);
        $mform->addElement('select', 'lec_end_time_in_min', 'End Lec Time in (Min)', $start_lec_time_min);
        $mform->addElement('textarea', 'comment', 'Comment / Remark', 'wrap="virtual" rows="10" cols="50",required');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Request");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        if (date("Y-m-d",time()+(86400*7)) > date('Y-m-d',$data['request_date'])){
            $errors['request_date'] = 'Request Date should be greater than '.date("d-m-Y",time()+(86400*7));
        }

        return $errors;
    }
}

class send_email extends moodleform {
    function definition(){
        global $COURSE;
        $mform =& $this->_form;
        $course_list = convert_std_to_array_centername(get_course_list());
        $select_course = $mform->addElement('select', 'course', 'Courses', $course_list);
        $select_course->setMultiple(true);
        $attributes=array('size'=>'46');
        $mform->addElement('text', 'email_subject',  'Email Subject',$attributes);
        $mform->addElement('textarea', 'email_content', 'Body', 'wrap="virtual" rows="10" cols="50",required');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Submit");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}

// attendance record date selector form //

class student_attendance_form extends moodleform {
    //Add elements to form
    function definition(){
        $mform =& $this->_form;
        $date_option = array(
            'startyear'=>2017,
            'stopyear'=>2020
            );
        $mform->addElement('date_selector','startdate','From Date',$date_option);
        $mform->addElement('date_selector','enddate','To Date',$date_option);
        //$this->add_action_buttons();
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submitbutton', "Get Biometric Records");
        //$buttonarray[] = $mform->createElement('reset', 'resetbutton', get_string('revert'));
        //$buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}