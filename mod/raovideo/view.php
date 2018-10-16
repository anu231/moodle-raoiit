<?php

require_once('../../config.php');
require_once('mod_form.php');
require_once('lib.php');
require_once('akamai_token_v2.php');
require_once('renderer.php');
 $id = required_param('id', PARAM_INT);    // Course Module ID

// Variables
global $DB, $PAGE,$OUTPUT;
require_login();
// Get the instance
if ($id) {
     $cm = get_coursemodule_from_id('raovideo', $id, 0, false, MUST_EXIST);
     $course = $DB->get_record('course', array('id'=>$cm->course), '*', MUST_EXIST);
     $raovideo = $DB->get_record('raovideo', array('id'=>$cm->instance), '*', MUST_EXIST);
     $vid_id = $raovideo->id;
    
} else {
    error('Your must specify a course_module ID or an instance ID');
}

$PAGE->set_pagelayout('standard');
$PAGE->set_url('/mod/raovideo/view.php', array('id' => $id));
$output = $PAGE->get_renderer('raovideo');
$renderable = new view_raovideo($vid_id);
echo $OUTPUT->header();
echo $output->render($renderable);
echo $output->footer();