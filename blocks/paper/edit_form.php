<?php

class block_paper_edit_form extends block_edit_form {
    
    protected function specific_definition($mform) {
        
        // Section header title according to language file.
        $mform->addElement('header', 'configheader', 'Edit papers for students');
        
        
        $mform->addElement('text', 'config_text', get_string('blockstring', 'block_paper'));
        $mform->setDefault('config_text', 'default value');
        $mform->setType('config_text', PARAM_RAW);
        
        $mform->addElement('text', 'config_title', get_string('blocktitle', 'block_paper'));
        $mform->setDefault('config_title', 'default value');
        $mform->setType('config_title', PARAM_TEXT);
        
    }
}