<?php
$day_setting = new admin_setting_configtext(
            'library/issuedays',
            'Days to return the book in',
            'Standard days the book should be returned in','7'
        );
//$day_setting->set_lockable(true);
$settings->add($day_setting);
$fine_setting = new admin_setting_configtext(
            'library/fine',
            'Fine Per Day',
            'Standard Fine amount','30'
        );
//$fine_setting->set_lockable(true);
$settings->add($fine_setting);

$course_setting = new admin_setting_configtext(
            'library/manager_course',
            'Course for admin access to library',
            'Course for admin access to library','branchadministration'
        );
//$course_setting->set_lockable(true);
$settings->add($course_setting);
/* 
$settings->add(new admin_setting_configcheckbox(
            'simplehtml/Allow_HTML',
            get_string('labelallowhtml', 'block_simplehtml'),
            get_string('descallowhtml', 'block_simplehtml'),
            '0'
        ));
*/