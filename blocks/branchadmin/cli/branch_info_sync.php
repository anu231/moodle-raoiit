<?php
/*
Syncs the batches and centers from analysis
*/

//define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
//require_once($CFG->libdir.'/clilib.php');

function get_url_data($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //set the url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //return as a variable
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle
    if( $response )
        return json_decode($response, true);
    else{
        echo "Error getting data from :".$url;
        return array();
    }
}

function sync_objects($analysis, $mdl, $fieldnames, $table){
    global $DB;
    $change = false;
    if ($mdl!=null){
        //do fieldwise comaprison
        foreach($fieldnames as $field){
            if ($mdl[$field] != $analysis[$field]){
                $change = true;
                $mdl[$field] = $analysis[$field];
            }
        }
        if ($change){
            $DB->update_record($table,$mdl);
        }
    } else{
        //create new record
        $change = true;
        $mdl = $analysis;
        $mdl['analysis_id'] = $analysis['id'];
        $DB->insert_record($table, $mdl);
    }
    
}

function sync_centres(){
    global $DB;
    //fetch all the centers from analysis
    $fetch_centre_url = 'http://analysis.raoiit.com/app/edumate/get_centers.php';
    $centre_list = get_url_data($fetch_centre_url);
    //echo $centre_list;
    //$mdl_centrelist = $DB->get_records('centre_info');
    $field_names = array('name','nearybycentres','zone','status');
    foreach ($centre_list as $centre){
        $mdl_centre = $DB->get_record('branchadmin_centre_info',array('analysis_id'=>$centre['id']));
        sync_objects($centre, $mdl_centre, $field_names, 'branchadmin_centre_info');
    }
}

function sync_batches(){
    global $DB;
    //fetch all the centers from analysis
    $fetch_batches_url = 'http://analysis.raoiit.com/app/edumate/get_batches.php';
    $batch_list = get_url_data($fetch_batches_url);
    $field_names = array('name','centreid','targetyear','batch','visibletoall','iszenith','ismd','status');
    foreach ($batch_list as $batch){
        $mdl_ttbatch = $DB->get_record('branchadmin_ttbatches',array('analysis_id'=>$batch['id']));
        sync_objects($batch, $mdl_ttbatch, $field_names, 'branchadmin_ttbatches');
    }
}

sync_centres();
sync_batches();