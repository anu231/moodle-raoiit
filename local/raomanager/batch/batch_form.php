<?php

// Feedback form

require_once("$CFG->libdir/formslib.php");
require_once("../lib.php");
class local_raomanager_batch_form extends moodleform {
    function definition() {
        global $DB;

        $mform =& $this->_form;

        $mform->addElement('header', 'add', 'Add/Edit batch');

        $id = $this->_customdata['id'];
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'batch','Batch Name');
        $mform->setType('batch', PARAM_NOTAGS);

        $CENTERS = $DB->get_records_menu('raomanager_centers', $conditions=array(), $sort=null, $fields='id, name');
        $mform->addElement('select', 'centerid', "Center", $CENTERS);

        $this->add_action_buttons();
    }
}