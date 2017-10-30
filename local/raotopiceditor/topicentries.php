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
$edit_entry = null;

foreach($form_class_names as $form_name){
    $form_type = explode('_', $form_name)[1];
    $form_objects[$form_type] = new $form_name(null, array('topic'=>$topicid, 'type'=>$form_type));
    if ($form_data = $form_objects[$form_type]->get_data()){
        //form has received data in it
        //process the data
        //the display the form
        $entry = new stdClass();
        $entry->name = $form_data->name;
        $entry->value = $form_data->value;
        $entry->topicid = $form_data->topic;
        $entry->type = $form_data->type;
        $entry->id = $form_data->id;
        add_topic_entry($entry);
        redirect('/local/raotopiceditor/topicentries.php?topic='.$form_data->topic);
    }
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
    
} else if ($action == 'edit' && $action_entry != null){
    //move this entry down
    //load the entry into the specific form
    //get the topic entry
    $edit_entry = get_topic_entry($action_entry);
}


//display the forms
foreach($form_objects as $type=>$fobj){
    if ($edit_entry != null && $edit_entry->type == $type){
        $fentry = new stdClass();
        $fentry->name = $edit_entry->name;
        $fentry->value = $edit_entry->value;
        $fentry->id = $edit_entry->id;
        $fobj->set_data($fentry);
    }
    $fobj->display();
}


$renderable = new topic_entries($topicid);
echo $output->render($renderable);
echo $output->footer();