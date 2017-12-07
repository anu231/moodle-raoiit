<?php
require_once($CFG->libdir . '/adminlib.php');

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $ADMIN->add('localplugins',
        new admin_externalpage('local_raowebservice',
        get_string('pluginname', 'local_raowebservice'),
        new moodle_url('/local/raowebservice/index.php')));
}