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
$action = optional_param('action',null, PARAM_TEXT);
$action_entry = optional_param('entry', null, PARAM_INT);

$output = $PAGE->get_renderer('local_raotopiceditor');
echo $output->header();

//adding new entries
$form_class_names = array('raotopiceditor_booklet_form', 'raotopiceditor_video_form', 'raotopiceditor_link_form');
$form_objects = Array();
foreach($form_class_names as $form_name){
    $form_objects[] = new $form_name(null, array('topic'=>$topicid, 'type'=>explode('_', $form_name)[1]));
}

foreach($form_objects as $fobj){
    if ($form_data = $fobj->get_data()){
        //form has received data in it
        //process the data
        //the display the form
        $entry = new stdClass();
        $entry->name = $form_data->name;
        $entry->value = $form_data->value;
        $entry->topicid = $form_data->topic;
        $entry->type = $form_data->type;
        add_topic_entry($entry);
        redirect('/local/raotopiceditor/topicentries.php?topic='.$form_data->topic);
    }
    $fobj->display();
}

if ($action == 'del' && $action_entry != null){
    //delete that entry
    delete_topic_entry($action_entry);
} else if (($action == 'up' || $action =='down') && $action_entry != null){
    //move this entry up
    require_once($CFG->dirroot.'/local/raotopiceditor/locallib.php');
    if (function_exists('movetopicentry')){
        movetopicentry($action_entry, $topicid, $action);
    } else {
        echo 'PROBLEM';
    }
    
}// else if ($action == 'down' && $action_entry != null){
    //move this entry down
//}


$renderable = new topic_entries($topicid);
echo $output->render($renderable);
echo $output->footer();