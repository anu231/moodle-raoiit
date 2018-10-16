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

function get_grievance_responses($gid){
    global $DB;
    $resp = $DB->get_records('grievance_responses',array('grievance_id'=>$gid, 'approved'=>1),'timecreated');
    return $resp;
}

function post_grievance_reply($gid, $reply_text, $dep, $approved){
    global $DB, $USER;
    //check if grievance exists for this $gid
    $g = $DB->get_record('grievance_entries',array('id'=>$gid));
    if ($g == NULL){
        return NULL;
    }
    $reply = new stdClass();
    $reply->grievance_id = $gid;
    $reply->deptid = $dep;
    $reply->email = $USER->username;
    $reply->approved = 1;
    $reply->timecreated = time();
    $reply->body = $reply_text;
    $resp_id = $DB->insert_record('grievance_responses',$reply, true);
    $resp = $DB->get_record('grievance_responses',array('id'=>$resp_id));
    return $resp;
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