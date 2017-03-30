<?php

// Feedback form

require_once("$CFG->libdir/formslib.php");

class local_raomanager_batch_form extends moodleform {
    function definition() {
        $mform =& $this->_form;

        $mform->addElement('header', 'add', 'Add/Edit batch');
        // $BATCHES = array(
        //     0 => "Please select a batch",
        // );
        // $batches = $mform->addElement('select', 'batch', 'Batch', $BATCHES);
        // $batches->setSelected(0);
        
        $id = $this->_customdata['id'];
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $mform->addElement('text', 'batch','Batch Name');
        $mform->setType('batch', PARAM_NOTAGS);

        $this->add_action_buttons();
    }
}