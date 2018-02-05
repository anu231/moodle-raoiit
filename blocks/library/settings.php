<?php
$day_setting = new admin_setting_text(
            'library_issuedays',
            'Days to return the book in',
            'Standard days the book should be returned in'
        );
$settings->add($day_setting);

$fine_setting = new admin_setting_configtext(
            'library/fine',
            'Fine Per Day',
            'Standard Fine amount','30'
        );
$settings->add($fine_setting);

$course_setting = new admin_setting_configtext(
            'library/manager_course',
            'Course for admin access to library',
            'Course for admin access to library','branchadministration'
        );
$settings->add($course_setting);

$reissue_setting = new admin_setting_configtext(
            'library/reissue',
            'Reissue Limitation',
            'Reissue allowed after days','7'
        );
$settings->add($reissue_setting);
/* 
$settings->add(new admin_setting_configcheckbox(
            'simplehtml/Allow_HTML',
            get_string('labelallowhtml', 'block_simplehtml'),
            get_string('descallowhtml', 'block_simplehtml'),
            '0'
        ));
*/