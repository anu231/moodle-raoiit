<?php


/**
 * Creates a new activity instance by either assigning an existing booklet or uploading and assigning
 * a new one
 * [NOTE] If the user does both the actions, Assignment of the existing booklet will be prioritized
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once('locallib.php');
class mod_paper_mod_form extends moodleform_mod {

    function definition() {
        global $CFG, $DB, $PAGE;

        $mform =& $this->_form;
        
        // Including Javascript
        $PAGE->requires->js("/mod/paper/js/paper.js");

        $mform =& $this->_form;


        // Inject json into a hidden field for front end processing
        $papers = paper_remote_fetch_papers();  // papers['names'] and papers['info']
        $mform->addElement('hidden', 'paperinfo', json_encode($papers['info'])); // Paperinfo for javascript


        // Populate the select element with papers
        $PAPERS = array('0'=>'Select a paper');
        foreach ($papers['names'] as $paper) {
            $PAPERS["$paper->id"] = $paper->name;
        }
        $name = $mform->addElement('select', 'paperid', 'Paper name', $PAPERS, array('onchange' => 'javascript:updateFields();', 'required'));
        $name->setSelected('0');
        $mform->addRule('paperid', 'Select a paper, ', 'required', null, 'client');
        


        $STANDARDS = array(
            "0" => 'Please select a Standard',
            "6" => 'VI', 
            "7" => 'VII',
            "8" => 'VIII',
            "9" => 'IX', 
            "10" => 'X', 
            "11" => 'XI', 
            "12" => 'XII',
            "r" => 'Repeater'
        );
        $standard = $mform->addElement('select', 'standard', 'Standard', $STANDARDS );
        $standard->setSelected("0");
        $mform->addRule('standard', 'Select a standard, ', 'required', null, 'client');
        


        $STREAMS = array(
            "0" => 'Please select a Stream',
            "engineering" => 'Enginnering',
            "medical" => 'Medical',
            "prefoundation" => 'Pre-foundation'
        );
        $stream = $mform->addElement('select', 'stream', 'Stream', $STREAMS);
        $stream->setSelected("0");
        $mform->addRule('stream', 'Select a stream ', 'required', null, 'client');

        // Display date
        // $mform->addElement('html', '<span id="date">');
        $mform->addElement('static', 'date', 'Date', '<span id="date">Please select a paper</span>', array('id'=>'cholo'));
        // $mform->addElement('html', '</span>');

        // Display duration
        $mform->addElement('static', 'duration', 'Duration', '<span id="duration">Please select a paper</span>', array('id' => 'duration')); // TODO Make into static


        $mform->addElement('textarea', 'syllabus', 'Syllabus', 'rows="5" cols="50"');
        $mform->setType('syllabus', PARAM_NOTAGS);
        $mform->addRule('syllabus', 'Syllabus cannot be empty, ', 'required', null, 'client');        

        // Marking scheme block
        $mform->addElement('static', 'markingscheme', 'Marking Scheme', '<span id="markingscheme">Please select a paper</span>');

        // Instructions text area
        $mform->addElement('textarea', 'instructions', 'Paper instructions', 'rows="5" cols="50"');
        $mform->setType('instructions', PARAM_NOTAGS);
        $mform->addRule('instructions', 'Please write proper instructions for the students', 'required', null, 'client');
        
        // Link to solution
        $mform->addElement('text', 'solutions', 'Link to solutions');
        $mform->setType('solutions', PARAM_NOTAGS);
        // $mform->addRule('solutions', 'Please add a link to the solutions', 'required', null, 'client');    // ? Not required for now

        // Offline selection
        $OFFLINE = array(
            '0' => '',
            'yes' =>  'Yes',
            'no' => 'No'
        );
        $offline = $mform->addElement('select', 'offline', 'Offline', $OFFLINE);
        $offline->setSelected('0');
        $mform->addRule('offline', 'Please select whether the paper is Offline or not', 'required', null, 'client');


        // Add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        // Moodle submit/cancel buttons
        $this->add_action_buttons();
    }
}

