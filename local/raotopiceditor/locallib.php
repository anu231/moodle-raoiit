<?php

function sync_dtp_topics(){
    global $CFG, $DB;
    //fetch dtp topics
    $url = $CFG->django_server.'dtp/topics_edumate/';
    echo $url;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false
    ));
    $resp = curl_exec($ch);
    //echo $resp;
    $dtp_topics = json_decode($resp);
    //print_r($dtp_topics);
    //get the topics currently in DB
    $edumate_topics = $DB->get_records('raotopiceditor_topics',array());
    $edu_topic_list = Array();
    foreach($edumate_topics as $topic){
        $edu_topic_list[$topic->portalref] = $topic;
    }
    //print_r($edumate_topics);
    
    foreach($dtp_topics as $dtop){
        //echo $dtop->id.PHP_EOL;
        if (array_key_exists($dtop->id, $edu_topic_list)){
            //check if there is an update
            if ($dtop->name != $edu_topic_list[$dtop->id]->name){
                $edu_topic_list[$dtop->id]->name = $dtop->name;
                //save this entry
                echo 'update'.PHP_EOL;
                $DB->update_record('raotopiceditor_topics',$edu_topic_list[$dtop['id']]);
            }
        } else{
            //need to create the new topic
            $etop = new stdClass();
            $etop->portalref = $dtop->id;
            $etop->name = $dtop->name;
            $etop->subject = array_search($dtop->subject, $CFG->SUBJECTS);
            echo 'insert'.PHP_EOL;
            $DB->insert_record('raotopiceditor_topics', $etop);
        }
    }
}

function list_topics(){
    global $DB;
    $topics = $DB->get_records('raotopiceditor_topics',array());
    return $topics;
}

function get_topic_entries($topicid){
    global $DB;
    $entries = $DB->get_records('raotopiceditor_topic_entries',array('topicid'=>$topicid),'sort');
    return $entries;
}

function max_sort_value_topic($topicid){
    global $DB;
    $max_value = $DB->get_record_sql('select max(sort) as maxval from {raotopiceditor_topic_entries} where topicid=?',array($topicid));
    return $max_value->maxval;
}

function add_topic_entry($entry){
    //get the last sort value
    global $DB;
    $last_sort = max_sort_value_topic($entry->topicid);
    $entry->sort = $last_sort + 1;
    $DB->insert_record('raotopiceditor_topic_entries', $entry);
}

function delete_topic_entry($entry){
    global $DB;
    $DB->delete_records('raotopiceditor_topic_entries',array('id'=>$entry));
}

function move_entry_up($entryid, $topic){
    $topic_entries = get_topic_entries($topic);
    foreach($topic_entries as $entry){
        
    }
}