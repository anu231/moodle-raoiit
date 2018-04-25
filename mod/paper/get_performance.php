<?php

require_once('../../config.php');
require_once('locallib.php');
global $DB, $COURSE, $USER;

 $id = required_param('id', PARAM_INT);

require_login();
// Get the instance
if ($id) {
    $cm = get_coursemodule_from_id('paper', $id, 0, false, MUST_EXIST);
    $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
    $paper = $DB->get_record('paper', array('id'=>$cm->instance), '*', MUST_EXIST);
    //print_r($paper);
    //check if user is enrolled in the course
    $context = context_course::instance($course->id);
    if (!is_enrolled($context, $USER->id, '', true)){
        return json_encode(array('error'=>'User does not have access to this paper'));
    }
} else {
    error('Your must specify a course_module ID or an instance ID');
    return json_encode(array('error'=>'Your must specify a course_module ID or an instance ID'));
}

//$performance = get_performance($USER->username, $paper->paperid);
// /$performance = get_performance(920471, 1601);
$performance = get_performance(920471, 1601);
$performance = format_performance($performance);

echo json_encode($performance);