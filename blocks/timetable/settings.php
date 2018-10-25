<?php

$faculty_course = new admin_setting_configtext(
    'timetable/course',
    'Faculty course id',
    'Enter Faculty course ID','15,16'
);
$settings->add($faculty_course);


            