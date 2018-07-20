<?php
require_once(__DIR__.'/../../config.php');
require_once(__DIR__.'/../timetable/locallib.php');
require_once('../../vendor/autoload.php');
function get_centers(){
    global $DB;
    $centers = $DB->get_records('branchadmin_centre_info',array('status'=>1));
    return $centers;
}



/*
gets the centre id of the current user
*/
function get_user_center($userid=null){
    global $DB, $CFG;
    $user_id = null;
    if ($userid==null){
        global $USER;
        $user_id = $USER->id;
    } else{
        $user_id = $userid;
    }
    $res = $DB->get_record('user_info_data',array('userid'=>$user_id,'fieldid'=>$CFG->CENTER_FIELD_ID));
    if ($res != null){
        return $res->data;
    }else {
        return null;
    }
}

function get_center_obj($centre_name){
    global $DB;
    $center = $DB->get_records_sql('select * from {branchadmin_centre_info} where name like ? and status=1',array($centre_name));
    if (count($center)>=0){
        return $center[key($center)];
    } else {
        return false;
    }
}

function get_batch_name($batchid){
    global $DB;
    $batchname = $DB->get_record('branchadmin_ttbatches',array('analysis_id'=>$batchid));
    return $batchname->name;
}

function get_center_name($cid){
    global $DB;
    $cname = $DB->get_record('branchadmin_centre_info',array('analysis_id'=>$cid));
    return $cname->name;
}

function get_batches_for_user(){
    //gets the names of all batches with the current user
    //get centre 
    //CHANGES MADE FOR ANALYSIS_ID
    global $DB, $USER;
    $sql = <<<EOT
    select tt.analysis_id as id, tt.name as name 
    from (select id, analysis_id, name, centreid from {branchadmin_ttbatches} where status=1) as tt 
    join (select data as d, userid as uid from {user_info_data} as udata join {user_info_field} as uif on udata.fieldid=uif.id where uif.shortname='center') as ud 
    join {branchadmin_centre_info} as ci on tt.centreid=ci.analysis_id and ci.analysis_id=ud.d where ud.uid=?
EOT;
    $batches = $DB->get_records_sql($sql, array($USER->id));
    return $batches;
}

function get_batch_names($batches){
    global $DB;
    $batch_ids = $DB->get_records_list('branchadmin_ttbatches','id',$batches,null,'name');
    $bids = array();
    foreach($batch_ids as $bid){
        array_push($bids,$bid->name);
    }
    return $bids;
}

function get_students_by_batch($batch){
    global $DB, $CFG;
    $sql = <<<SQL
    select u.id, concat(u.username," - ",u.firstname," ",u.lastname) as name 
    from {user} as u join 
    (select udd.userid as userid, udd.data as data from {user_info_data} as udd join {user_info_field} as uif on udd.fieldid = uif.id where uif.shortname='batch') as ud 
    on u.id=ud.userid where ud.data=?
SQL;
    //get all the users with the specified batches
    $users = $DB->get_records_sql($sql,array($batch));
    return $users;
}

function fetch_numbers_for_userids($userid_list){
    global $DB;
    //fetch the ids for mobile numnbers
    $field_ids = $DB->get_records_list('user_info_field','name',array('studentmobile','fathermobile','mothermobile'),null,'id');
    $field_id_list = array();
    foreach($field_ids as $fi){array_push($field_id_list,$fi);}
    foreach($userid_list as $userid){
        $data = $DB->get_records_list('user_info_data','fieldid',$field_id_list,null,'data');
    }
}

function fetch_numbers_for_userid($userid){
    global $DB;
    
}

function sendSMS(&$s_mobile, &$s_text){
        //initialize the request variable
        $success = '';
        $error = '';
        $request = "";
        //this is the key of our sms account
        $param["workingkey"] = "3693f2jl9yh7375b0o1i";
        //this is the message that we want to send
        $param["message"] = stripslashes($s_text);
        //these are the recipients of the message
        $param["to"] = $s_mobile;
        //this is our sender
        $param["sender"] = "RAOIIT";

        //traverse through each member of the param array
        foreach($param as $key=>$val){
            //we have to urlencode the values
            $request.= $key."=".urlencode($val);
            //append the ampersand (&) sign after each paramter/value pair
            $request.= "&";
        }
        //remove the final ampersand sign from the request
        $request = substr($request, 0, strlen($request)-1); 

        //this is the url of the gateway's interface
        $url = "http://alerts.prioritysms.com/api/web2sms.php";

        //initialize curl handle
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); //set the url
        curl_setopt($ch, CURLOPT_POST, count($param)); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 	
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
        $response = curl_exec($ch); //run the whole process and return the response
        curl_close($ch); //close the curl handle

        $responseary = explode("Invalid/DND Numbers:", $response);

        if (substr(trim($responseary[0]), 0, 12) == "Message GID=") // delivered successfully
        {
            $successary = explode(",", trim($responseary[0]));
            $success .= "Message successfully delivered to " . sizeof($successary) . " mobile numbers.";
            return true;
        }
        if (strlen(trim($responseary[1])) != 0)	// errors
        {
            $this->error .= "Invalid/DND Numbers: " . trim($responseary[1]);
            return false;
        }
    }

    function get_user_center_batch_analysis($userid, $link){
        /*
        assumes the connection to analysis db has already been made
        */
        $sql = 'select centre, ttbatchid from userinfo where userid='.$userid;
        $res = $link->query($sql);
        if(!$res){
            return False;
        }
        $row = $res->fetch_assoc();
        if(!$row){return False;}
        else {return $row;}
    }
    
function load_field_records_edumate($table_name){
    global $DB;
    $res = $DB->get_records($table_name,array());
    $data = Array();
    foreach($res as $r){
        $data[$r->analysis_id] = $r->id;
    }
    return $data;
}

/***************************************STUDENT PERFORMANCE RELATED FUNCTIONS****************/
function get_student_token($userid){
    global $CFG;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $CFG->django_server.'api-auth-token',
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => array(
            'username' => $userid,
            'auth_id' => $CFG->django_auth_id
        )
    ));
    $resp = curl_exec($ch);
    curl_close($curl);
    if ($resp){
        return $resp;
    } else{
        return curl_error($ch);
    }
}

function get_student_full_report($userid){
    global $CFG;
    $url = $CFG->django_server.'student/spr?auth='.$CFG->django_auth.'&username='.$userid;
    $ch = curl_init();
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false
    ));
    $resp = curl_exec($ch);
    if ($resp){
        return json_decode($resp);
    } else{
        $msg = new stdClass();
        $msg->error = curl_error($ch);
        return $msg;
    }

}
/********************************************************************************************/
/*********************************************SMS RELATED FUNCTIONS**************************/
function bulk_fetch_numbers_for_students($students){
    global $DB;
    $userids = array_values($students);
    list($usql, $params) = $DB->get_in_or_equal($userids);
    $sql = <<<EOT
    select distinct(uid.data) from (select id from {user_info_field} where shortname like '%mobile') as fid
    join
    {user_info_data} as uid
    on fid.id = uid.fieldid
    where uid.userid $usql
EOT;
    //$userids = implode(',',$userid_array);
    $res = $DB->get_records_sql($sql, $params);
    return $res;
}
/********************************************************************************************/
/*********************************************Branch Timetable RELATED FUNCTIONS**************************/
function get_batches_branch($branchid){
    //gets the batches belonging to the specific branch
    //$branchid is the analysis id
    global $DB;
    $nearby_centers = $DB->get_record('branchadmin_centre_info',array('analysis_id'=>$branchid));
    //$nearby_centers = (implode(',',explode(',',$nearby_centers->nearbycentres)));
    $sql = <<<SQL
    select * from {branchadmin_ttbatches} 
    where centreid in ($nearby_centers->nearbycentres) 
    and status=1
SQL;
    $res = $DB->get_records_sql($sql);
    return $res;
    /*$batchids = Array();
    foreach($res as $r){
        $batchids[] = $res->analysis_id;
    }
    return implode(',',$batchids);*/
}

/********************************************************************************************/
function get_course_list(){
global $DB;
$query = "SELECT id, fullname, shortname from {course}";
$courselist = $DB->get_records_sql($query);
return $courselist;
}

function convert_std_to_array_centername($tl){
    $ts_arr = Array();
    foreach($tl as $t){
        $ts_arr[$t->id] = $t->id." - ".$t->fullname;
    }
    return $ts_arr;
}
// Sendgrid email function //

function send_email_centres($student_name,$student_email)
		{
			$email_message= "$student_name,<br/><br/>
			This mail is to inform you that schedule & all details of <b>Mathematics Olympiad 2018-19</b> have been announced. Basic information is mentioned below & for more detail, visit below link of Rao IIT Academy blog and Mathematics Olympiad official website.<br/><br/>
			Rao Academy blog: <a href='https://raoiit.blog/2018/06/13/mathematical-olympiad-2018-19/'>https://raoiit.blog/2018/06/13/mathematical-olympiad-2018-19/</a><br/><br/>
			Official Website: <a href='http://olympiads.hbcse.tifr.res.in/olympiads-2018-19/mathematical-olympiad/'>http://olympiads.hbcse.tifr.res.in/olympiads-2018-19/mathematical-olympiad/</a>  & <a href='http://www.mtai.org.in/prmo/'>http://www.mtai.org.in/prmo/</a>  <br/><br/>
			<b>Stage 1: Pre- Regional Mathematical Olympiad (PRMO)</b><br/><br/>	
			<ul>
			<li><b>Exam Date & Time:</b> 10:00 AM – 1:00 PM (3 Hrs.); 19th August 2018</li>
			<li><b>Number of Question:</b> 30 (The answer to each question is either a single digit number or a two-digit number)</li>
			<li><b>Medium of Paper:</b> Hindi and English Medium (*Students who require the Hindi version will need to indicate their choice at the time of registration for PRMO.)</li>
			<li><b>Eligibility:</b> Candidates born on or after August 1, 1999 and are studying in <b>Class 8, 9, 10, 11 or 12,</b> are eligible to write PRMO 2018. Further candidates must be Indian citizens.</li>
			<li><b>Examination Fee:</b> Rs.220</li>
			<li><b>Registration Process:</b> Students are required to visit registered RMO centers for registration between <b>15th  June – 30th June 2018.</b> Registered centers will be published on <b>15th June 2018</b> at official website only.</li>
			<li><b>Issue of Hall Tickets:</b> 07 August 2018</li>
			<li><b>Result Declaration:</b>By 15th September 2018</li>
			</ul>
			<br></br>
			<b>Stage 2: Regional Mathematical Olympiad (RMO)</b><br/><br/>	
			<ul>
			<li><b>Exam Date:</b> 1.00 PM and 4.00 PM Sunday, 7th October 2018</li>
			<li>The RMO is a three-hour written test with six problems</li>
			<li><b>Result Declaration:</b> On or before 6th December 2018</li>
			</ul>
			<br></br>
			<b>Stage 3: Indian National Mathematical Olympiad (INMO)</b><br/><br/>	
			<ul>
			<li><b>Exam Date:</b> 12.00 PM to 4.00 PM, January 20, 2019</li>
			<li>This contest is a four-hour written test.</li>
			<li><b>Result Declaration:</b> last week of February 2019</li>
			</ul>
			<b>Syllabus for Mathematical Olympiad:</b> The syllabus for Mathematical Olympiad (pre-regional, regional, national and international) is pre-degree college mathematics. The areas covered are <b>arithmetic of integers, geometry, quadratic equations and expressions, Trigonometry, co-ordinate geometry, system of linear equations, permutations and combination, factorization of polynomial, Inequalities, Elementary combinatorics, Probability theory and number theory, Finite series and complex numbers, Elementary graph theory.</b> The syllabus does not include calculus and statistics. The major areas from which problems are given are algebra, combinatorics, geometry and number theory. The syllabus is in a sense spread over Class XI to Class XII levels, but the problems under each topic involve high level of difficulty and sophistication. The difficulty level increases from <b>PRMO to RMO to INMO to IMO.</b><br/><br/>
			In case of any queries or doubts, contact your branch<br/><br/>
			-------------<br>
				Thanks & Regards<br>
				Rao IIT Academy ";

			$from = new Email("Rao IIT Academy", "sg@raoiit.com");
			$subject = "Mathematics Olympiad 2018-19 (PRMO, RMO, INMO)";
			$to = new Email($student_name,$student_email);
			$content = new Content("text/html",$email_message);
			$mail = new Mail($from, $subject, $to, $content);

			//$cc = new Email('Edumate', "edumate@raoiit.com");                // extract email id if more than one
			//$mail->personalization[0]->addCc($cc);

			$apiKey = "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";
			$sg = new \SendGrid($apiKey);

			$response = $sg->client->mail()->send()->post($mail);

			//echo "<pre>";
		   //print_r($response);
			$res_code=$response->statusCode();
			// echo $res_code;
			$status_info=implode(" ",$response->headers());
			$send_date = date('Y-m-d H-i-s');
			echo $student_name ." - ".$student_email." StatusCode ".$res_code."\n";
		}