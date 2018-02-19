<?php

defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\core\event\user_loggedin',
        'callback'  => 'block_branchadmin_observer::usercreated',
        'internal'  => false // This means that we get events only after transaction commit.
        //'priority'  => 1000,
    ),
);