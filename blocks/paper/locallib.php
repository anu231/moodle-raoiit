<?php
require($CFG->dirroot.'/vendor/autoload.php');
require($CFG->dirroot.'/vendor/rmccue/requests/library/Requests.php'); //Requests.php


/**
 * Saves or updates a paper
 * @param: $mform
 * @param: int $courseid
 */
function paper_add_paper(stdClass $mform, $courseid) {
    global $DB, $COURSE;

    // Fill in invisible fields.
    $paperinfo = json_decode($mform->paperinfo);
    foreach ($paperinfo as $paper) {
        if($mform->name == $paper->id){
            $mform->name = $paper->name; // $mform->name is actually a paperid. Replace that with paper name
            $mform->paperid = $paper->id; // Add paperid to $mform;
            $mform->date = $paper->startdate;
            $mform->duration = $paper->time;
            $mform->markingscheme = paper_generate_markingscheme($paper); // TODO
            break;
        }
    }
    $mform->courseid = $courseid;
    $mform->timecreated = time();
    $mform->timemodified = time();


    if( $result = $DB->insert_record('block_paper', $mform) ) {
        return $result;
    } else {
        return FALSE;
    }

}

function paper_update_paper(){
    return;
}

/**
 * Returns marking scheme converted to json
 * @param paper $paper
 * @return json
 */
function paper_generate_markingscheme($paper){
    $prefix = ['sc', 'mc', 'ar', 'ch', 'tf', 'fb', 'ms'];
    $schemearray = array();
    foreach ($prefix as $p) {
        $schemearray[$p.'cor'] = $paper->{$p.'cor'};
        $schemearray[$p.'neg'] = $paper->{$p.'neg'};
        $schemearray[$p.'negmarks'] = $paper->{$p.'negmarks'};
    }
    return json_encode($schemearray);
}

/**
 * Returns paper names json from django server
 * Used for creating paper instance 
 *
 * @return json
 */
function paper_remote_fetch_papers() {
    $SERVERURL = "http://192.168.1.19:8000";
    $PAPERNAMEURL = "/paper_names/";
    $PAPERINFOURL = "/papers/";
    $headers = array('Accept' => 'application/json');

    $request = Requests::get($SERVERURL.$PAPERNAMEURL, $headers);
    $names = json_decode($request->body);

    $request = Requests::get($SERVERURL.$PAPERINFOURL, $headers);
    $info = json_decode($request->body);

    $json = array(
        'names' => $names,
        'info' => $info
    );
    return $json;
}
