<?php
require_once($CFG->dirroot.'/vendor/autoload.php');
// require($CFG->dirroot.'/vendor/rmccue/requests/library/Requests.php'); //Requests.php


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


    if( $result = $DB->insert_record('paper', $mform) ) {
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
    global $CFG;
    $SERVERURL = $CFG->django_server;
    $PAPERNAMEURL = "paper_names/";
    $PAPERINFOURL = "papers/";
    $headers = array('Accept' => 'application/json');

    // $request = Requests::get($SERVERURL.$PAPERNAMEURL, $headers);
    $names = paper_get_request($SERVERURL.$PAPERNAMEURL);

    // $request = Requests::get($SERVERURL.$PAPERINFOURL, $headers);
    $info = paper_get_request($SERVERURL.$PAPERINFOURL);

    $json = array(
        'names' => $names,
        'info' => $info
    );
    return $json;
}
/**
*fetch paper information of the provided paper id
*
*/
function paper_remote_fetch_info($id){
    global $CFG;
    $SERVERURL = $CFG->django_server;
    $PAPERINFOURL = "paper_info?pid=".$id;
    $headers = array('Accept' => 'application/json');

    // $request = Requests::get($SERVERURL.$PAPERINFOURL, $headers);
    $info = paper_get_request($SERVERURL.$PAPERINFOURL);

    return $info;
}

// Use curl request to get paper data
// @return json
function paper_get_request($url) {
    //initialize curl handle
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //set the url
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //return as a variable
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle
    if( $response )
        return json_decode($response);
    else{
        echo "Error getting papers";
        return array();
    }
}