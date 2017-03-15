<?php

/**
 * forms.php
 * Contains various forms
 */
defined('MOODLE_INTERNAL') || die();
require_once("$CFG->libdir/formslib.php");
require_once($CFG->dirroot.'/course/moodleform_mod.php');


/**
 * Form for entering/editing data about a mod_raobooklet_edit_form
 */
class mod_raobooklet_edit_form extends moodleform {

    function definition() {
        global $CFG;

        $mform =& $this->_form;

        $mform->addElement('text', 'bookletid', 'ID of the booklet file');
        $mform->setType('name', PARAM_INT);

        $mform->addElement('text', 'name', 'Name of the booklet file');
        $mform->setType('name', PARAM_NOTAGS);

        $SUBJECTS = array(
            "none" => 'Please select a Subject', 
            "physics" => 'Physics',
            "chemistry" => 'Chemistry',
            "maths" => 'Maths',
            "biology" => 'Biology',
            "zoology" => 'Zoology'
        );
        $subject = $mform->addElement('select','subject', 'Subject', $SUBJECTS );


        $mform->addElement('text', 'topics', 'Topics in this booklet ');
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


        $mform->addElement('text', 'notes', 'Private notes (optional)');
        $mform->setType('notes', PARAM_NOTAGS);


        $DOWNLOADABLE = array('No', 'Yes');
        $downloadable = $mform->addElement('select', 'downloadable', 'Allow student to download file?', $DOWNLOADABLE);
        $downloadable->setSelected(0);

        // Moodle submit/cancel buttons
        $this->add_action_buttons();
    }
}


/**
 * Contains filemanager element for managing uploaded files
 */
class mod_raobooklet_upload_form extends moodleform {
    function definition() {
        global $CFG;

        $mform =& $this->_form;

        $mform->addElement('filemanager', 'attachments', 'Upload Raobookletsv2', null,
                    array('subdirs' => 0, 0, 'areamaxbytes' => 500485760, 'maxfiles' => 300));

        // Moodle submit/cancel buttons
        $this->add_action_buttons();
    }
}


// Feedback form
class mod_raobooklet_feedback_form extends moodleform {
    function definition() {
        $mform =& $this->_form;

        $RATINGS = array(
            "1" => "Disliked it",
            "2" => "Could be improved",
            "3" => "Satisfied",
            "4" => "Loved it",
            "5" => "Amazing!"
        );
        $rating = $mform->addElement('select', 'rating', 'Rating', $RATINGS);
        $rating->setSelected(3);
        
        $mform->addElement('textarea', 'comment','Comment', 'rows="8" cols="55" max-cols="70"');

        $this->add_action_buttons();
    }
}