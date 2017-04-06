<?php

// Feedback form

require_once("$CFG->libdir/formslib.php");
require_once("../lib.php");

class local_raomanager_admin_form extends moodleform {
    function definition() {
        global $DB, $PAGE;
        $PAGE->requires->js('/local/raomanager/templates/js/admin_form.js'); // Include autofill js
        $mform =& $this->_form;


        $mform->addElement('header', 'add', 'Add/Edit Rao Admins');
        
        $id = $this->_customdata['id'];
        $mform->addElement('hidden', 'id', $id);
        $mform->setType('id', PARAM_INT);

        $PLUGINS = local_raomanager_pluginmap();
        $plugins = $mform->addElement('select', 'pluginname', 'Select Plugin to assign', $PLUGINS);
        $mform->addRule('pluginname','Required','required',null,'client');


        $mform->addElement('text', 'username','Enter a Username');
        $mform->setType('username', PARAM_NOTAGS);
        $mform->addRule('username','Required','required',null,'client');

        $mform->addElement('html', '<table id="autofill" class="table table-striped table-hover table-bordered"><tr><td>No Results Found</td></tr></table>');

        $this->add_action_buttons();
    }
}