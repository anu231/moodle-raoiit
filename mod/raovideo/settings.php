<?php

defined('MOODLE_INTERNAL') || die;
if ($ADMIN->fulltree) {
    $video_window = new admin_setting_configtime(
                'raovideo/hours','raovideo/minutes',
                'Seconds the video url should be valid',
                'Seconds the video url should be valid',
                array('h'=>1,'m'=>0)
            );
    $settings->add($video_window);

    $akamai_key = new admin_setting_configtext(
                'raovideo/akamai_key',
                'Akamai Secret Key',
                'Akamai Secret Key',''
            );
    $settings->add($akamai_key);

    $akamai_server = new admin_setting_configtext(
                'raovideo/akamai_server',
                'Akamai Server URl',
                'akamai_server',''
            );
    $settings->add($akamai_server);
}