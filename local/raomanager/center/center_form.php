<?php

// Feedback form

require_once("$CFG->libdir/formslib.php");
require_once("../lib.php");
class local_raomanager_center_form extends moodleform {
    function definition() {
        global $DB;

        $mform =& $this->_form;

        $mform->addElement('header', 'add', 'Add/Edit Center');

        $id = $this->_customdata['id'];
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'name','Center Name');
        $mform->setType('name', PARAM_NOTAGS);

        $mform->addElement('text', 'zone','Zone');
        $mform->setType('zone', PARAM_NOTAGS);

        $this->add_action_buttons();
    }
}