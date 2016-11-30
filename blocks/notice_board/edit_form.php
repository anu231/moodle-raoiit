<?php

class block_notice_board_edit_form extends block_edit_form {
    protected function specific_definition($mform){
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));

        // Notice Title required
        $mform->addElement('text', 'config_noticetitle', get_string('noticetitle', 'block_notice_board'));
        $mform->setDefault('config_noticetitle', 'Add Notice title here');
        $mform->setType('config_noticetitle', PARAM_RAW);

        // Notice body html editor
        $mform->addElement('editor', 'config_noticebody', get_string('noticebody', 'block_notice_board'));
        $mform->setDefault('config_noticebody', 'Add Notice body here');
        $mform->setType('config_noticebody', PARAM_RAW);
    }
}