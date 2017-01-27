<?php
defined('MOODLE_INTERNAL') || die();

$observers = array(
    array(
        'eventname' => '\core\event\cohort_deleted',
        'callback'  => '\local_djangouser\observer::create_user_django',
    ),

    array(
        'eventname' => '\core\event\cohort_member_added',
        'callback'  => '\local_cohortrole\observers::cohort_member_added',
    ),

    array(
        'eventname' => '\core\event\cohort_member_removed',
        'callback'  => '\local_cohortrole\observers::cohort_member_removed',
    ),

    array(
        'eventname' => '\core\event\role_deleted',
        'callback'  => '\local_cohortrole\observers::role_deleted',
    ),
);

