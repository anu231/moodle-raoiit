<?php

$capabilities = array(
    'local/raomanager:editbatches' => array(
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'editingteacher' => CAP_PREVENT,
            'manager' => CAP_ALLOW
        ),
        'clonepermissionsfrom' => 'moodle/course:manageactivities'
    ),

    'local/raomanager:view' => array(
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,
        'archetypes' => array(
            'guest' => CAP_PREVENT,
            'student' => CAP_PREVENT,
            'teacher' => CAP_PREVENT,
            'editingteacher' => CAP_PREVENT,
            'manager' => CAP_ALLOW
        )
    ),

);