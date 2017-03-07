<?php

// The main configuration form

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/moodleform_mod.php');

// optional_param() // While updating
class mod_raobooklet_mod_form extends moodleform_mod {
    
    function definition() {
        global $CFG;

        $mform =& $this->_form;

        $mform->addElement('text', 'name', 'Name of the booklet');
        $mform->setType('name', PARAM_NOTAGS);
        // // Testing
        // $this->standard_intro_elements('Cholo');
        // // 
        $SUBJECTS = array(
            "none" => 'Please select a Subject', 
            "physics" => 'Physics',
            "chemistry" => 'Chemistry',
            "maths" => 'Maths',
            "biology" => 'Biology',
            "zoology" => 'Zoology'
        );
        $subject = $mform->addElement('select','subject', 'Subject', $SUBJECTS );



        $mform->addElement('filepicker', 'attachment', 'Attachment');

        $mform->addElement('text', 'topics', 'Topics');
        $mform->setType('topics', PARAM_NOTAGS);

        $STANDARDS = array(
            "none" => 'Please select a Standard',
            "6" => 'VI', 
            "7" => 'VII',
            "8" => 'VIII',
            "9" => 'IX', 
            "10" => 'X', 
            "11" => 'XI', 
            "12" => 'XII'
        );
        $standard = $mform->addElement('select', 'standard', 'Standard', $STANDARDS );
        $standard->setSelected(0);

        $YEARS = array(
            "none" => 'Please select Year of publishing',
            "2016" => '2016', 
            "2017" => '2017', 
            "2018" => '2018', 
            "2019" => '2019', 
            "2020" => '2020', 
            "2021" => '2021',
            "2022" =>  '2022'
        );
        $mform->addElement('select', 'year', 'Publishing Year', $YEARS);

        $mform->addElement('text', 'description', 'Description for students (optional)');
        $mform->setType('description', PARAM_NOTAGS);
        

        $mform->addElement('text', 'notes', 'Private notes (optional)');
        $mform->setType('notes', PARAM_NOTAGS);
        

        $DOWNLOADABLE = array('No', 'Yes');
        $downloadable = $mform->addElement('select', 'downloadable', 'Allow student to download file?', $DOWNLOADABLE);
        $downloadable->setSelected(0);

        // Add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        // Moodle submit/cancel buttons
        $this->add_action_buttons();
    }
}

