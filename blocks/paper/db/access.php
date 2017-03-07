<?php 
$capabilities = array(

'block/paper:myaddinstance' => array(
    'captype' => 'write',
    'contextlevel' => CONTEXT_SYSTEM,
    'archetypes' => array(
        'user' => CAP_ALLOW
    ),
    'clonepermissionfrom' => 'moodle/my:manageblocks'
),

'block/paper:addinstance' => array(
    'riskbitmask' => RISK_SPAM | RISK_XSS,
    'captype' => 'write',
    'contextlevel' => CONTEXT_BLOCK,
    'archetypes' => array(
        'editingteacher' => CAP_ALLOW,
        'manager' => CAP_ALLOW
    ),
    'clonepermissionsfrom' => 'moodle/my:manageblocks'
),

// Assign papers to courses
'block/paper:assignpaper' => array(
    'riskbitmask' => RISK_SPAM | RISK_XSS,
    'captype' => 'write',
    'contextlevel' => CONTEXT_COURSE,
    'archetypes' => array(
        'editingteacher' => CAP_ALLOW,
        'manager' => CAP_ALLOW,
        'manager' => CAP_ALLOW,
    ),
    'clonepermissionsfrom' => 'moodle/my:manageblocks'
)
);



?>