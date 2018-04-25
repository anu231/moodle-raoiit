<?php
require_once($CFG->dirroot.'/vendor/autoload.php');
require_once($CFG->libdir.'/raolib.php');
// require($CFG->dirroot.'/vendor/rmccue/requests/library/Requests.php'); //Requests.php


/**
 * Saves or updates a paper
 * @param: $mform
 * @param: int $course
 */
function paper_add_paper(stdClass $mform, $course) {
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
    $mform->course = $course;
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
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle
    if( $response )
        return json_decode($response);
    else{
        echo "Error getting papers";
        return array();
    }
}

function get_performance($username, $pid){
    global $CFG;
    $url = $CFG->django_server."student/spr?auth=v1Bdyp&username=$username&pid=$pid";
    $resp = fetch_json_web_resource($url);
    return $resp;
}

function get_paper_subjects($pinfo){
    $subj = array();
    $subj_map = array(
        'phy'=>'p', 'chem'=>'c', 'math'=>'m','eng'=>'e','sci'=>'sc','bio'=>'b','soc'=>'ss','mat'=>'ma','lr'=>'lr','cs'=>'cs','gk'=>'gk'
    );
    foreach($subj_map as $sub=>$code){
        //$access_str = 'n'.$sub;
        if ($pinfo->{'n'.$sub} > 0){
            //array_push($subj, $code);
            $subj[$code] = $pinfo->{'n'.$sub};
        }
    }
    return $subj;
}

function format_performance($performance){
    $subj_map = array(
        'p'=>'Physics',
        'c'=>'Chemistry',
        'm'=>'Mathematics',
        'b'=>'Botany',
        'z'=>'Zoology',
        'ma'=>'Mental Ability',
        'e'=>'English',
        'lr'=>'Logical Reasoning',
        'ss'=>'Social Science',
        'cs'=>'Computer Sc',
        'gk'=>'General Knowledge',
        'sc'=>'Science'
    );
    $formatted_performance = array();
    $subjects = get_paper_subjects($performance->paper_info);
    $max_performance = get_performance('999991', $performance->paper_info->id);
    //array_push($subjects, 'marks');
    $total = array();
    $total['name'] = 'Total';
    $total['obt'] = $performance->marksobt;
    $total['negmarks'] = $performance->negmarks;
    $total['corr'] = 0;
    $total['wrong'] = 0;
    $total['unattempt'] = 0;
    $total['corr_accuracy'] = 0;
    $total['corr_percent'] = 0;
    $total['wrong_percent'] = 0;
    $total['unattempt_percent'] = 0;
    $total['marks_correct'] = $total['obt']+(-1)*$total['negmarks'];
    $total['rank'] = $performance->rank;
    $total['max_obt'] = $max_performance->marksobt;
    $total_ques = 0;
    foreach($subjects as $subj=>$cnt){
        $subj_p = array();
        $subj_p['name'] = $subj_map[$subj];
        $subj_p['obt'] = $performance->{$subj.'obt'};
        $subj_p['corr'] = $performance->{$subj.'corr'};
        $subj_p['wrong'] = $performance->{$subj.'wrong'};
        $subj_p['negmarks'] = $performance->{$subj.'negmarks'};
        $subj_p['unattempt'] = $cnt-($subj_p['corr']+$subj_p['wrong']);
        $subj_p['corr_accuracy'] = round($subj_p['corr']*100/($subj_p['corr']+$subj_p['wrong']));
        $subj_p['corr_percent'] = round($subj_p['corr']*100/$cnt);
        $subj_p['wrong_percent'] = round($subj_p['wrong']*100/$cnt);
        $subj_p['unattempt_percent'] = round($subj_p['unattempt']*100/$cnt);
        $subj_p['marks_correct'] = $subj_p['obt']+(-1)*$subj_p['negmarks'];
        $subj_p['rank'] = $performance->{$subj.'rank'};
        $subj_p['max_obt'] = $max_performance->{$subj.'obt'};
        $subj_p['nques'] = $cnt;
        $total['corr'] += $subj_p['corr'];
        $total['wrong'] += $subj_p['wrong'];
        $total['unattempt'] += $subj_p['unattempt'];
        $formatted_performance[$subj] = $subj_p;   
        $total_ques +=$cnt;
    }
    $total['corr_accuracy'] = round($total['corr']*100/($total['corr']+$total['wrong']));
    $total['corr_percent'] = round($total['corr']*100/$total_ques);
    $total['wrong_percent'] = round($total['wrong']*100/$total_ques);
    $total['unattempt_percent'] = round($total['unattempt']*100/$total_ques);
    $total['nques'] = $total_ques;
    $formatted_performance['total'] = $total;
    return $formatted_performance;
}