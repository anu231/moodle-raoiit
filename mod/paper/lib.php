<?php

require_once('locallib.php');

/**
 * Saves or updates a paper
 * @param: $mform
 * @param: int $courseid
 */
function paper_add_instance($raobooklet, $mform=NULL){
    global $DB, $COURSE;

    // Fill in invisible fields.
    $paperinfo = json_decode($raobooklet->paperinfo);
    foreach ($paperinfo as $paper) {
        if($raobooklet->paperid == $paper->id){
            $raobooklet->name = $paper->name;
            $raobooklet->paperid = $paper->id; // Add paperid to $mform;
            $raobooklet->date = $paper->startdate;
            $raobooklet->duration = $paper->time;
            $raobooklet->markingscheme = paper_generate_markingscheme($paper); // TODO
            break;
        }
    }
    $raobooklet->courseid = $COURSE->id;
    $raobooklet->timecreated = time();
    $raobooklet->timemodified = time();


    if( $result = $DB->insert_record('paper', $raobooklet) ) {
        return $result;
    } else {
        return FALSE;
    }
}



function paper_update_instance($raobooklet, $mform=NULL){
    global $DB, $COURSE;

    // Fill in invisible fields.
    $paperinfo = json_decode($raobooklet->paperinfo);
    foreach ($paperinfo as $paper) {
        if($raobooklet->paperid == $paper->id){
            $raobooklet->name = $paper->name;
            $raobooklet->paperid = $paper->id; // Add paperid to $mform;
            $raobooklet->date = $paper->startdate;
            $raobooklet->duration = $paper->time;
            $raobooklet->markingscheme = paper_generate_markingscheme($paper); // TODO
            break;
        }
    }
    $raobooklet->courseid = $COURSE->id;
    $raobooklet->timecreated = time();
    $raobooklet->timemodified = time();

    $raobooklet->id = $raobooklet->instance;
    if( $result = $DB->update_record('paper', $raobooklet) ) {
        return $result;
    } else {
        return FALSE;
    }


}

function paper_delete_instance($id){
    global $DB;
    return $DB->delete_records('paper', array('id'=> $id));
}