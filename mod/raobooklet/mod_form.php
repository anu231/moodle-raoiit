<?php


/**
 * Creates a new activity instance by either assigning an existing booklet or uploading and assigning
 * a new one
 * [NOTE] If the user does both the actions, Assignment of the existing booklet will be prioritized
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_raobooklet_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $DB;

        $mform =& $this->_form;
        // Instructions
        $mform->addElement('html', '<h3><u>Either Assign an existing booklet OR Upload and Assign a new one</u></h3><hr>');

        // Assign from a dropdown
        $mform->addElement('header', 'assign', 'Assign an existing booklet');
        $mform->setExpanded('assign');
        $mform->closeHeaderBefore('assign');

        $BOOKLETS = array(0=>'Select a booklet') + $DB->get_records_menu('raobooklet_info', null, $sort='', $fields='bookletid, name');
        $booklets = $mform->addElement('select', 'bookletid', 'Booklet', $BOOKLETS);
        $booklets->setSelected(0);

        $customname = $mform->addElement('text', 'customname', 'Name of the Booklet');

        // Dont show this part when updating an instance
        if(! $this->current->id) {
            // Upload new file
            $mform->addElement('header', 'uploadnew', 'Upload and assign a new booklet');

            $mform->addElement('filepicker', 'attachment', 'Attachment');
                        
            $mform->addElement('html', '<b><u>Fill in the information about the uploaded booklet</u></b><hr>');

            $mform->addElement('text', 'filename', 'Name of the booklet file');
            $mform->setType('name', PARAM_NOTAGS);

            $customname = $mform->addElement('text', 'customname', 'Name of the Booklet');            

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
        }

        // Add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        // Moodle submit/cancel buttons
        $this->add_action_buttons();
    }
}

