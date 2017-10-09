<?php

require_once('../../config.php');
require_once('renderer.php');
require_once('topic_form.php');

global $PAGE, $CFG;
require_login();
$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/raotopiceditor/topicentries.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_heading('Rao Topic Editor');

$topicid = required_param('topic',PARAM_INT);
$output = $PAGE->get_renderer('local_raotopiceditor');
$renderable = new topic_entries($topicid);

echo $output->header();

//adding new entries
$form_class_names = array('raotopiceditor_booklet_form');
$form_objects = Array();
foreach($form_class_names as $form_name){
    $form_objects[] = new $form_name(null, array('topic'=>$topicid));
}

foreach($form_objects as $fobj){
    if ($form_data = $fobj->get_data()){
        //form has received data in it
        //process the data
        //the display the form
    }
    $fobj->display();
}


echo $output->render($renderable);
echo $output->footer();