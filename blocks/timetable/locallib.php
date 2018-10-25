<?php

function get_subject_name($shortname){
    // subject mapping with subject shortname//
    $subj_map = array(
		'p'=>'physics',
		'c'=>'chemistry',
		'm'=>'mathematics',
		'z'=>'zoology',
		'b'=>'botany'
    );
    if (!array_key_exists($shortname, $subj_map)){
        return 'Unknown Subject';
    } else {
        return $subj_map[$shortname];
    }

}
function x_week_range(&$start_date, &$end_date, $date) {
    $ts = strtotime($date);// date mapping 
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts); // date mapping with last sunday (turnary condition)
    $start_date = date('Y-m-d', $start); // current week start date 
    $end_date = date('Y-m-d', strtotime('next sunday', $start)); // next week date time
    if (date('w',$ts)==6){ // if date is equal to 6 days then calculate end of the week date
        $end_date = date('Y-m-d',strtotime('next sunday',strtotime($end_date)));
    }
}

function connect_analysis_db(){
    global $CFG;
    $link = new mysqli($CFG->analysis_db_host,$CFG->analysis_db_user,$CFG->analysis_db_password,$CFG->analysis_db_name); // analysis db creditial
    return $link;
}

function close_analysis_db($link){
    $link->close(); //close analysis db connection
}



function get_student_batches($link, $user){
    //assumes the db connections have already been made
    /*$batches = '';
    $qry = "select ttbatchid, extbatchid, centre from userinfo where userid=".$username;
    $res = $link->query($qry);
    if (!$res){
        return False;
    }
    $row = $res->fetch_assoc();
    if (!$row){return False;}
    if ($row['extbatchid']!=''){
        $batches = $row['ttbatchid'].','.$row['extbatchid'];
    } else{
        $batches = $row['ttbatchid'];
    }
    //get the student improvement session batch
    $qry_center = "select id from ttbatches where (name like 'Student Improvement Session') and centreid=".$row['centre'];
    $res_center = $link->query($qry_center);
    if (!$res_center){
        return $batches;
    }
    while( $row_center = $res_center->fetch_assoc()){
        $batches .= ','.$row_center['id'];
    }
    return $batches;*/
    global $CFG, $DB;
    require_once($CFG->libdir.'/raolib.php'); // raolib file required
    //$user = $DB->get_record('user', array('username'=>$username));
    $batch_data = get_rao_user_profile_fields(array('center','batch','extbatchid'),$user); // get user custom profile field data
    //get student improvement batch
    $sib_sql = <<<SQL
    select analysis_id from {branchadmin_ttbatches} where centreid=? and name like 'Student Improvement Session'
SQL;
    // select query from branchadmin_ttbatches where center id and name is Student Improvement Session
    $sib = $DB->get_records_sql($sib_sql,array($batch_data['center']));// execute query
    $batches = ''; // varibale initilize
    if (isset($batch_data['batch'])){$batches = $batch_data['batch'];} // given batch data or timetable
    if (isset($batch_data['extbatchid']) && $batch_data['extbatchid'] != ''){$batches .= ','.$batch_data['extbatchid'];} // given extra batch data or timetable like Rao college or super zenith extra batch
    if (count($sib)>0){
        $sib = $sib[array_keys($sib)[0]]->analysis_id;
        return $batches.','.$sib;
    } else {
        return $batches;
    }
}

function get_timetable($start_date,$end_date,$user=null,$batchids=null){
    $link = connect_analysis_db(); // analysis DB credition
    $batches = null;
    if ($user != null){
        $batches = get_student_batches($link, $user); // get student batches 
    }else if ($batchids != null){
        $batches = $batchids; // batch id store in variable
    } else {
        return false;
    }

    $qry = "SELECT S.sid,S.batchid, S.lecturenum, S.testnum, S.date, S.from, S.to, S.facultyid, S.event, S.iscancel, S.istest, B.centreid, B.targetyear, B.batch, B.name AS batchname, C.name AS centrename, T.targetyear, T.batch, T.name AS classname, F.id AS ffid, F.name AS facultyname, F.shortname, F.type as facultytype, F.subject, Z.name as topicname, Y.type AS testtype FROM schedule AS S, ttbatches AS B, centreinfo AS C, classes AS T, facultyinfo AS F, topics AS Z, testtypes AS Y WHERE S.batchid=B.id AND B.centreid=C.id AND T.targetyear=B.targetyear AND T.batch=B.batch AND S.facultyid=F.id AND S.topicid=Z.id AND S.testtype=Y.id AND `date` >= '$start_date' AND `date` <= '$end_date' AND B.id IN ($batches) ORDER BY `date`, `from`";
    $res = $link->query($qry);
    // Above query fetching timetable information with start date, end date and batch id's
    if (!$res){
        return false;
    }
    $processed_lectures = array(); // array initialize
    $index = 0; // initial index variable value is 0 
    $days_added_map = array(); // array initialize
    while($lecture = $res->fetch_assoc()){
        //$day = array();
        //foreach($lectures as $lecture){
        $tmp = array();
        $tmp['sid'] = $lecture['sid'];
        $tmp['fancydate'] = date('Y-m-d',strtotime($lecture['date']));
        //$tmp['date'] = "{$lecture['d']}-{$lecture['m']}-{$lecture['y']}";
        $tmp['starttime'] = date('H:i',strtotime($lecture['from']));//$lecture['sh'].':'.$lecture['sm'];
        $tmp['endtime'] = date('H:i',strtotime($lecture['to']));//$lecture['eh'].':'.$lecture['em'];
        //$tmp['teacher'] = isset($lecture['sn']) ? $lecture['sn'] : '';
        $tmp['istest'] = $lecture['istest'];//web service
        if ($lecture['istest']=='0'){
            $tmp['teacher'] = $lecture['shortname']; // subject shortname
            $tmp['subject'] = get_subject_name($lecture['subject']); // subject name
            $tmp['topicname'] = $lecture['topicname'];// get topic name
            $tmp['notes'] = $lecture['event']; // event or notes defined by planning dept
        } else{
            $tmp['notes'] = $lecture['testtype'].'-'.$lecture['testnum'].' '.$lecture['event'];
            //entering null values for the purpose of web service
            $tmp['teacher'] = '';
            $tmp['subject'] = '';
            $tmp['topicname'] = '';
        }
        $tmp['cancelclass'] = $lecture['iscancel'] == '1' ? 'cancelled' : '';
        $tmp['batch'] = $lecture['centrename'].' - '.$lecture['batchname'];
        if (!array_key_exists($tmp['fancydate'],$days_added_map)){
            $days_added_map[$tmp['fancydate']] = $index;
            $processed_lectures[$index] = array();
            $processed_lectures[$index]['items'] = array();
            $processed_lectures[$index]['fancydate'] = $tmp['fancydate'];
            $index++;
        }
        array_push($processed_lectures[$days_added_map[$tmp['fancydate']]]['items'],$tmp);
    }
    //print_r($processed_lectures);
    close_analysis_db($link); // close analysis db 
    return $processed_lectures;
}

// get faculty timetable //
function get_faculty_timetable($start_date,$end_date,$faculty){
    $link = connect_analysis_db();

  $qry = "SELECT S.sid,S.batchid, S.lecturenum, S.testnum, S.date, S.from, S.to, S.facultyid, S.event, S.iscancel, S.istest, B.centreid, B.targetyear, B.batch, B.name AS batchname, C.name AS centrename, T.targetyear, T.batch, T.name AS classname, F.id AS ffid, F.name AS facultyname, F.shortname, F.type as facultytype, F.subject, Z.name as topicname, Y.type AS testtype FROM schedule AS S, ttbatches AS B, centreinfo AS C, classes AS T, facultyinfo AS F, topics AS Z, testtypes AS Y WHERE S.batchid=B.id AND B.centreid=C.id AND T.targetyear=B.targetyear AND T.batch=B.batch AND S.facultyid=F.id AND S.topicid=Z.id AND S.testtype=Y.id AND `date` >= '$start_date' AND `date` <= '$end_date' AND S.facultyid=$faculty->empid ORDER BY `date`, `from`";
    $res = $link->query($qry);
// above query fetching faculty timetable information through analysis
    if (!$res){
        return false;
    }
    $processed_lectures = array();
    $index = 0;
    $days_added_map = array();
    while($lecture = $res->fetch_assoc()){
        //$day = array();
        //foreach($lectures as $lecture){
        $tmp = array();
        $tmp['sid'] = $lecture['sid'];
        $tmp['fancydate'] = date('Y-m-d, D',strtotime($lecture['date']));
        //$tmp['date'] = "{$lecture['d']}-{$lecture['m']}-{$lecture['y']}";
        $tmp['starttime'] = date('H:i',strtotime($lecture['from']));//$lecture['sh'].':'.$lecture['sm'];
        $tmp['endtime'] = date('H:i',strtotime($lecture['to']));//$lecture['eh'].':'.$lecture['em'];
        //$tmp['teacher'] = isset($lecture['sn']) ? $lecture['sn'] : '';
        $tmp['istest'] = $lecture['istest'];//web service
        if ($lecture['istest']=='0'){
            //$tmp['teacher'] = $lecture['shortname'];
            $tmp['subject'] = get_subject_name($lecture['subject']);
            $tmp['topicname'] = $lecture['topicname'];
            $tmp['notes'] = $lecture['event'];
        } else{
            $tmp['notes'] = $lecture['testtype'].'-'.$lecture['testnum'].' '.$lecture['event'];
            //entering null values for the purpose of web service
            $tmp['teacher'] = '';
            $tmp['subject'] = '';
            $tmp['topicname'] = '';
        }
        $tmp['cancelclass'] = $lecture['iscancel'] == '1' ? 'cancelled' : '';
        $tmp['batch'] = $lecture['centrename'].' - '.$lecture['batchname'];
        if (!array_key_exists($tmp['fancydate'],$days_added_map)){
            $days_added_map[$tmp['fancydate']] = $index;
            $processed_lectures[$index] = array();
            $processed_lectures[$index]['items'] = array();
            $processed_lectures[$index]['fancydate'] = $tmp['fancydate'];
            $index++;
        }
        array_push($processed_lectures[$days_added_map[$tmp['fancydate']]]['items'],$tmp);
    }

    close_analysis_db($link);
    return $processed_lectures;
}

// 


function get_week_start_end_dates(){
    date_default_timezone_set("Asia/Calcutta");
    /*$start_date = date("Y-m-d", strtotime('monday this week', strtotime(date('Y-m-d'))));
    $end_date = date("Y-m-d", strtotime('sunday this week', strtotime(date('Y-m-d'))));*/
    $ts = strtotime('now');
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    $start_date = date('Y-m-d', $start);  // Weekly start date 
    $end_date = date('Y-m-d', strtotime('next sunday', $start));  // Weekly end date 
    if (date('w',$ts)==6){
        $end_date = date('Y-m-d',strtotime('next sunday',strtotime($end_date)));
    }
    return array('start_date'=>$start_date,'end_date'=>$end_date);
}

function get_week_timetable(){
    global $USER;
    $dates = get_week_start_end_dates(); // getting weekly timetable
    return get_timetable($dates['start_date'],$dates['end_date'],$USER);
}


function get_completed_topics($username){ // detailed topic completion report
    global $CFG;
    $link = connect_analysis_db(); // analysis db creditial
    //get the batchid for student
    require_once("$CFG->dirroot/lib/raolib.php"); // required rao lib file
    $batch = get_rao_user_profile_fields(array('batch'));
    $batch=$batch['batch'];
    $qry = <<<SQL
    select top.subject as subj, top.name as topicname from 
    schedule as sch 
    join topics as top
    on sch.topicid=top.id
    where sch.batchid=$batch and sch.topicover=1 order by sch.date asc
SQL;
    $res = $link->query($qry);
    // above query fetching information perticular subject and topic name of perticular batch 
    // topic over 1 means topic is over
    if (!$res){
        return false;
    }
    $topics_over = array(); // array initialize
    $subj_index_map = array(); // array initialize
    $index = 0;
    while($row = $res->fetch_assoc()){
        if (!array_key_exists($row['subj'],$subj_index_map)){
            $topics_over[$index] = array('subject'=>get_subject_name($row['subj']),'items'=>array());
            $subj_index_map[$row['subj']] = $index;
            $index++;
        }
        array_push($topics_over[$subj_index_map[$row['subj']]]['items'],array('name'=>$row['topicname']));
    }
    close_analysis_db($link); // close analysis db 
    return $topics_over;
}
/*
arguments - username(roll number of the user)
description - fetches the ptm records from analysis server
return value - array of ('date', 'event')
*/
function get_ptm_records($username){
    global $CFG;
    $link = connect_analysis_db(); //analsis db connection
    require_once("$CFG->dirroot/lib/raolib.php"); 
    $center = get_rao_user_profile_fields(array('center'));//fetching the analysis center id
    $center=$center['center'];//get centername
    $qry = <<<SQL
    select sch.date as date, sch.event as event 
    from schedule as sch
    join (select id, centreid from ttbatches where name like '%Student Improvement Session%') as tt
    on sch.batchid = tt.id
    where tt.centreid=$center and sch.event like '%PTM%' order by sch.date asc; 
SQL;
    $res = $link->query($qry);
    if (!$res){
        return false;
    }
    $records = array();
    $index = 0;
    while($row = $res->fetch_assoc()){
        $records[$index] = array('date'=>$row['date'],'event'=>$row['event']);
        $index++;
    }
    close_analysis_db($link);
    return $records;

}

function check_batch_access($batchid){
    //checks if the current logged in user has access to the specified batch
    //batchid is the batchid in analysis and not the id in moodle tables
    //logic - 
    //the branch admin will be enrolled in branchadmin course whereas the student will not be enrolled in one
    global $USER, $CFG;
    if ($USER->id == 0){
        return False;
    }
    $course_context = context_course::instance($CFG->branchadmin_course_id);
    if (is_enrolled($course_context, $USER)){
        //enrolled in branchadmin, check the batches belonging  to the user's branch

    } else{
        //its a student
        
    }

}
