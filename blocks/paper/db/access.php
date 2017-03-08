<?php

$capabilities = array(
    'block/readytohelp:addinstance' => array(
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/course:manageactivities'
    ),

    'block/readytohelp:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_USER,
        'archetypes' => array(
            'guest' => CAP_PREVENT,
            'student' => CAP_ALLOW,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        )
    ),

    'block/readytohelp:approve' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => array(
            'student' => CAP_PREVENT,
            'guest' => CAP_PREVENT,
            'teacher' => CAP_ALLOW,
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/course:manageactivities'
    ),
);
