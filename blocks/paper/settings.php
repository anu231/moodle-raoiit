<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'block_paper'),
            get_string('descconfig', 'block_paper')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'paper/Allow_Expired',
            get_string('labelallowexpired', 'block_paper'),
            get_string('descallowexpired', 'block_paper'),
            '0'
        ));