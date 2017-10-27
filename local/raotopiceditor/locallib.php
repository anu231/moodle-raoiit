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
    $dtp_topics = json_decode($resp);
    //get the topics currently in DB
    $edumate_topics = $DB->get_records('raotopiceditor_topics',array());
    $edu_topic_list = Array();
    foreach($edumate_topics as $topic){
        $edu_topic_list[$topic->portalref] = $topic;
    }
    
    foreach($dtp_topics as $dtop){
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
            echo 'insert - '.$dtop->name.PHP_EOL;
            $DB->insert_record('raotopiceditor_topics', $etop);
        }
    }
}

function cache_topics_with_entries(){
    global $DB;
    $topics = $DB->get_records('raotopiceditor_topics',array());
    $topic_entries = Array();
    $topic_cache = cache::make('local_raotopiceditor', 'topicentries');
    foreach($topics as $topic){
        $cache_topic = $topic_cache->get($topic->id);
        if (!$cache_topic){
            //get all the entries in this topic
            $entries = get_topic_entries($topic->id);
            if (!$topic_cache->set($topic->id,json_encode($entries))){
                //error in setting the key
                //log this error
            }
        }
    }
    $topic_cache->set('topicentries',1);
    //return $topics;
}

function get_topic_list_array(){
    $top_array = Array();
    $topics = list_topics();
    foreach($topics as $topic){
        $top_array[$topic->id] = $topic->name; 
    }
    return $top_array;
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

function movetopicentry($entryid, $topic, $direction){
    global $DB;
    $topic_entries = get_topic_entries($topic);
    $prev_entry_index = -1;
    $curr_entry_index = -1;
    $entry_keys = array_keys($topic_entries);
    foreach($entry_keys as $key=>$entry_key){
        if ($topic_entries[$entry_key]->id == $entryid){
            $curr_entry_index = $key;
            break;
        }
        $prev_entry_index = $key;
    }
    $swap_index = -1;
    if ($curr_entry_index != -1){
        if ($curr_entry_index != 0 && $direction == 'up'){
            $swap_index = $prev_entry_index;
        } else if ($curr_entry_index < (count($topic_entries)-1) && $direction == 'down'){
            $swap_index = $curr_entry_index + 1;
        }
    }
    if ($swap_index != -1){
        $curr_entry_index = $entry_keys[$curr_entry_index];
        $swap_index = $entry_keys[$swap_index];
        $temp = $topic_entries[$swap_index]->sort;
        $topic_entries[$swap_index]->sort = $topic_entries[$curr_entry_index]->sort;
        $topic_entries[$curr_entry_index]->sort = $temp;
        //save the entries
        $DB->update_record('raotopiceditor_topic_entries', $topic_entries[$curr_entry_index]);
        $DB->update_record('raotopiceditor_topic_entries', $topic_entries[$swap_index]);
    }
}