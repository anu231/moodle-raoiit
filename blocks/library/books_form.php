<?php
defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once('locallib.php');
class add_books_form extends moodleform {
    //Add elements to form
    function definition(){

        $mform =& $this->_form;
        $mform->addElement('text', 'book_name', get_string('book_name', 'block_library'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addElement('text', 'volume', get_string('volume', 'block_library'));
        $mform->setType('volume', PARAM_INT); 
        $mform->addElement('text', 'publisher', get_string('publisher', 'block_library'));
        $mform->setType('publisher', PARAM_TEXT);
        $mform->addElement('text', 'author', get_string('author', 'block_library'));
        $mform->setType('author', PARAM_TEXT);
        $mform->addElement('text', 'price', get_string('price', 'block_library'));
        $mform->setType('price', PARAM_INT);
        $mform->addElement('text', 'barcode', get_string('barcode', 'block_library'),'maxlength="13" ');
        $mform->setType('barcode', PARAM_INT);
        $center_list = array(
        '1' => 'Thane (Lokpuram)',
        '2' => 'Thane',
        '3' => 'Dombivali',
        '4' => 'Powai (CSC)',
        '5' => 'Andheri',
        '6' => 'Kandivali (TVM)',
        '7' => 'Borivali',
        '8' => 'Kalyan (Birla)',
        '9' => 'Sion',
        '10' => 'Nerul',
        '11' => 'Kandivali (Thakur)',
        '12' => 'Kandivali (T.P. Bhatia)',
        '13' => 'Silvassa',
        '14' => 'Mumbai (HO)',
        '15' => 'Kota (Talwandi)'      
        );
        $mform->addElement('select', 'branch', get_string('branch', 'block_library'), $center_list);
        $options = array('1' => 'Available','0' => 'Lost');
        $select = $mform->addElement('select', 'status', get_string('status', 'block_library'), $options);
        $select->setSelected('1');
        $buttonarray=array();
        $buttonarray[] = $mform->createElement('submit', 'submit', "Add Books");
        $buttonarray[] = $mform->createElement('cancel');
        $mform->addGroup($buttonarray, 'buttonar', '', ' ', false);
    }
}