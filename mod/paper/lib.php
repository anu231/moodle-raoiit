<?php

require_once($CFG->dirroot.'/calendar/lib.php');
require_once('locallib.php');

/**
 * Saves or updates a paper
 * @param: $mform
 * @param: int $courseid
 */
function paper_add_instance($paper, $mform=NULL){
    global $DB, $COURSE;

    // Fill in invisible fields.
    /*$paperinfo = json_decode($paper->paperinfo);
    foreach ($paperinfo as $p) {
        if($paper->paperid == $p->id){
            $paper->name = $p->name;
            $paper->paperid = $p->id; // Add paperid to $mform;
            $paper->date = $p->startdate;
            $paper->duration = $p->time;
            $paper->markingscheme = paper_generate_markingscheme($p); // TODO
            break;
        }
    }*/
    $paper_info = paper_remote_fetch_info($paper->paperid);
    $paper->duration = $paper_info->time;
    $paper->markingscheme = paper_generate_markingscheme($paper_info);
    $paper->data = $paper_info->date;

    $paper->courseid = $COURSE->id;
    $paper->timecreated = time();
    $paper->timemodified = time();
    $paper->name = $paper_info->name;


    if( $result = $DB->insert_record('paper', $paper) ) {
        paper_add_calendar_event($paper, $result);
        return $result;
    } else {
        return FALSE;
    }
}



function paper_update_instance($paper, $mform=NULL){
    global $DB, $COURSE;

    // Fill in invisible fields.
    $paperinfo = json_decode($paper->paperinfo);
    foreach ($paperinfo as $p) {
        if($paper->paperid == $p->id){
            $paper->name = $p->name;
            $paper->paperid = $p->id; // Add paperid to $mform;
            $paper->date = $p->startdate;
            $paper->duration = $p->time;
            $paper->markingscheme = paper_generate_markingscheme($p); // TODO
            break;
        }
    }
    $paper->courseid = $COURSE->id;
    $paper->timecreated = time();
    $paper->timemodified = time();

    $paper->id = $paper->instance;
    if( $result = $DB->update_record('paper', $paper) ) {
        return $result;
    } else {
        return FALSE;
    }


}

function paper_delete_instance($id){
    global $DB;
    return $DB->delete_records('paper', array('id'=> $id));
}


function paper_add_calendar_event($paper, $instanceid){
    $event = new stdClass();
    $event->eventtype = "PAPER_CALENDAR_EVENT"; // Constant defined somewhere in your code - this can be any string value you want. It is a way to identify the event.
    $event->type = CALENDAR_EVENT_TYPE_STANDARD; // This is used for events we only want to display on the calendar, and are not needed on the block_myoverview.
    $event->name = $paper->name;
    $event->description = null;
    $event->courseid = $paper->courseid;
    $event->groupid = 0;
    $event->userid = 0;
    $event->modulename = 'paper';
    $event->instance = $instanceid;
    $event->timestart = strtotime($paper->date);
    $event->timestart = time();
    $event->timesort = $paper->date;
    $event->visible = instance_is_visible('paper', $paper);
    $event->timeduration = 0;
    
    calendar_event::create($event);
}