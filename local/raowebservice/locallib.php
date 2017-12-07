<?php




/**
 * Gets User course paper list
 * @param: $courseid 
 * @return: $paper_list - list of papers for user enrolled course
 */

function get_paper_list($courseid){
    
    global $USER, $DB;
    
    $result = $DB->get_records('paper', array('courseid' => $courseid));
    return $result;

}

/**
 * Gets User grievance list info
 * @param: 
 * @return: $grievance_result - list of grievance for user
 */
function get_grievance_list(){
    
    global $USER, $DB;
    $username = $USER->username;
    $grievance_list = $DB->get_records('grievance_entries', array('username' => $username));

    return $grievance_list;

}


/**
 * Gets User grievance response list
 * @param: $greivance_id 
 * @return: $greivance_response - list of responces for users grievance
 */
function get_greivance_response($greivance_id){
    

    global $USER, $DB;
    
    $greivance_response = $DB->get_records('grievance_responses', array('grievance_id' => $greivance_id));
    
    
    return $greivance_response;

}