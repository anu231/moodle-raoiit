<?php

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    ///  It must be included from a Moodle page
}
require_once($CFG->dirroot.'/course/moodleform_mod.php');
require_once($CFG->dirroot.'/mod/raovideo/lib.php');
class mod_raovideo_mod_form extends moodleform_mod {
 
    function definition() {
        global $CFG, $DB, $OUTPUT;
        $mform =& $this->_form;
        $mform->addElement('text', 'videoid', 'Video ID', array('size'=>'50'));
        $mform->setType('videoid', PARAM_TEXT);
        $mform->addElement('text', 'videoname', 'Video Name', array('size'=>'50'));
        $mform->setType('videoname', PARAM_TEXT);
        $mform->addElement('text', 'url', 'Video URL '. $CFG->akamai_server, array('size'=>'50'));
        $mform->setType('url', PARAM_TEXT);
        $this->standard_coursemodule_elements();
        $this->add_action_buttons();
    }
}