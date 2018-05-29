<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once('locallib.php');

class leave_application_form extends moodleform {
    function definition(){
        $mform =& $this->_form;
        
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('fullday', 'block_leaverecord'), 0);
        $radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('firsthalf', 'block_leaverecord'), 1);
        $radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('secondhalf', 'block_leaverecord'), 2);
        $radioarray[] = $mform->createElement('radio', 'leave_status', '', get_string('multipledays', 'block_leaverecord'), 3);
        $mform->addGroup($radioarray, 'radioar', '', array(' '), false);
        $mform->setDefault('leave_status', 0);

        $mform->addElement('date_selector', 'date', 'Apply Date');

        $mform->addElement('date_selector', 'leave_from', get_string('from'),['class' => 'leave']);
        $mform->addElement('date_selector', 'leave_to', get_string('to'),['class' => 'leave']);
        



        $mform->addElement('textarea', 'leave_reason', 'Leave Reason', 'wrap="virtual" rows="10" cols="50",required');
        $mform->setType('leave_reason', PARAM_RAW);

        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Apply leaves");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }

    function validation($data, $files){
        $errors = array();
        global $DB, $USER,$CFG;
        $book = $DB->get_records('lib_bookmaster', array("status"=>1));
        foreach($book as $result)
        {
             $bookid=$result->bookid;
             $bookname=$result->name;
             $bookbarcode=$result->barcode;
        }
        if($bookid == $data['bookid']){
            $errors['bookid'] = 'Book is Already present in database';
        }
        if($bookbarcode == $data['barcode']){
            $errors['barcode'] = 'Book Barcode is Already present in database';
        }
        if($bookname == $data['name']){
            $errors['name'] = 'Book Name is Already present in database';
        }
        if ($data['price'] != is_numeric($data['price'])){
            $errors['price'] = 'Please enter numeric value';    
        }
       
        return $errors;
    }
}
 