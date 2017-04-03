<?php

// Feedback form

require_once("$CFG->libdir/formslib.php");
require_once("locallib.php");

class local_raomanager_admin_form extends moodleform {
    function definition() {
        $mform =& $this->_form;

        $mform->addElement('header', 'add', 'Add/Edit Rao Admins');
        
        $id = $this->_customdata['id'];
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $PLUGINS = rm_admin_pluginmap();
        $plugins = $mform->addElement('select', 'pluginid', 'Select Plugin to assign', $PLUGINS);

        $mform->addElement('text', 'username','Enter a Username');
        $mform->setType('username', PARAM_NOTAGS);

        $this->add_action_buttons();
    }
}