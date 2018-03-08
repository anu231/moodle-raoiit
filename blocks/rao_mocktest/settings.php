<?php
$raotest_url = new admin_setting_configtext(
    'rao_mocktest/raotest_url',
    'URL of Rao Online Mocktest',
    'Standard URL to be set here with "?" sign in end of url'
);
$settings->add($raotest_url);

$paper_name = new admin_setting_configtext(
    'rao_mocktest/rao_papername',
    'Paper Name of Rao Online Mocktest',
    'Standard Paper name to be set here'
);
$settings->add($paper_name);