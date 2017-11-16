<?php
$day_setting = new admin_setting_text(
            'library_issuedays',
            'Days to return the book in',
            'Standard days the book should be returned in'
        );
$day_setting->set_lockable(true);
$settings->add($day_setting);
/* 
$settings->add(new admin_setting_configcheckbox(
            'simplehtml/Allow_HTML',
            get_string('labelallowhtml', 'block_simplehtml'),
            get_string('descallowhtml', 'block_simplehtml'),
            '0'
        ));
*/