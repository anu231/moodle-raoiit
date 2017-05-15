<?php

defined('MOODLE_INTERNAL') || die();
require_once("{$CFG->libdir}/formslib.php");
require_once($CFG->dirroot.'/blocks/branchadmin/locallib.php');

class branchadmin_sms_form extends moodleform{

    function definition(){
        $mform =& $this->_form;

        $batches = get_batches_for_user(get_user_center());

    }

}