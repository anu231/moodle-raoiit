<?php

function get_subject_name($shortname){
    $subj_map = array(
		'p'=>'physics',
		'c'=>'chemistry',
		'm'=>'maths',
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
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    $start_date = date('Y-m-d', $start);
    $end_date = date('Y-m-d', strtotime('next sunday', $start));
}

function connect_analysis_db(){
    global $CFG;
    //mysql_connect($CFG->analysis_host,$CFG->analysi_db_user,$CFG->analysis_db_pass)or die(mysql_error());
    //mysql_select_db($CFG->analysis_db) or die(mysql_error());
    $link = new mysqli($CFG->analysis_db_host,$CFG->analysis_db_user,$CFG->analysis_db_password,$CFG->analysis_db_name);
    /*if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }*/
    return $link;
}

function close_analysis_db($link){
    $link->close();
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
    require_once($CFG->libdir.'/raolib.php');
    //$user = $DB->get_record('user', array('username'=>$username));
    $batch_data = get_rao_user_profile_fields(array('center','batch','extbatchid'),$user);
    //get student improvement batch
    $sib_sql = <<<SQL
    select analysis_id from {branchadmin_ttbatches} where centreid=? and name like 'Student Improvement Session'
SQL;
    $sib = $DB->get_records_sql($sib_sql,array($batch_data['center']));
    $batches = '';
    if (isset($batch_data['batch'])){$batches = $batch_data['batch'];}
    if (isset($batch_data['extbatchid'])){$batches .= ','.$batch_data['extbatchid'];}
    $sib = $sib[array_keys($sib)[0]]->analysis_id;
    return $batches.','.$sib;
}

function get_timetable($start_date,$end_date,$user=null,$batchids=null){
    $link = connect_analysis_db();
    $batches = null;
    if ($user != null){
        $batches = get_student_batches($link, $user);    
    }else if ($batchids != null){
        $batches = $batchids;
    } else {
        return false;
    }
    
    $qry = "SELECT S.sid,S.batchid, S.lecturenum, S.testnum, S.date, S.from, S.to, S.facultyid, S.event, S.iscancel, S.istest, B.centreid, B.targetyear, B.batch, B.name AS batchname, C.name AS centrename, T.targetyear, T.batch, T.name AS classname, F.id AS ffid, F.name AS facultyname, F.shortname, F.type as facultytype, F.subject, Z.name as topicname, Y.type AS testtype FROM schedule AS S, ttbatches AS B, centreinfo AS C, classes AS T, facultyinfo AS F, topics AS Z, testtypes AS Y WHERE S.batchid=B.id AND B.centreid=C.id AND T.targetyear=B.targetyear AND T.batch=B.batch AND S.facultyid=F.id AND S.topicid=Z.id AND S.testtype=Y.id AND `date` >= '$start_date' AND `date` <= '$end_date' AND B.id IN ($batches) ORDER BY `date`, `from`";
    $res = $link->query($qry);
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
        $tmp['fancydate'] = date('Y-m-d',strtotime($lecture['date']));
        //$tmp['date'] = "{$lecture['d']}-{$lecture['m']}-{$lecture['y']}";
        $tmp['starttime'] = date('H:i',strtotime($lecture['from']));//$lecture['sh'].':'.$lecture['sm'];
        $tmp['endtime'] = date('H:i',strtotime($lecture['to']));//$lecture['eh'].':'.$lecture['em'];
        //$tmp['teacher'] = isset($lecture['sn']) ? $lecture['sn'] : '';
        $tmp['istest'] = $lecture['istest'];//web service
        if ($lecture['istest']=='0'){
            $tmp['teacher'] = $lecture['shortname'];
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

function get_week_start_end_dates(){
    date_default_timezone_set("Asia/Calcutta");
    /*$start_date = date("Y-m-d", strtotime('monday this week', strtotime(date('Y-m-d'))));
    $end_date = date("Y-m-d", strtotime('sunday this week', strtotime(date('Y-m-d'))));*/
    $ts = strtotime('now');
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    $start_date = date('Y-m-d', $start);
    $end_date = date('Y-m-d', strtotime('next sunday', $start));
    if (date('w',$ts)==6){
        $end_date = date('Y-m-d',strtotime('next sunday',strtotime($end_date)));
    }
    return array('start_date'=>$start_date,'end_date'=>$end_date);
}

function get_week_timetable(){
    global $USER;
    $dates = get_week_start_end_dates();
    return get_timetable($dates['start_date'],$dates['end_date'],$USER->username);
}

function get_completed_topics($username){
    $link = connect_analysis_db();
    $qry = "select top.subject as subj, top.name as topicname from schedule as sch join topics as top join (select ttbatchid as ttbatchid from userinfo where userid=".$username.") as user on sch.topicid=top.id and user.ttbatchid=sch.batchid where sch.topicover=1 order by sch.date asc";
    $res = $link->query($qry);
    if (!$res){
        return false;
    }
    $topics_over = array();
    $subj_index_map = array();
    $index = 0;
    while($row = $res->fetch_assoc()){
        if (!array_key_exists($row['subj'],$subj_index_map)){
            $topics_over[$index] = array('subject'=>get_subject_name($row['subj']),'items'=>array());
            $subj_index_map[$row['subj']] = $index;
            $index++;
        }
        array_push($topics_over[$subj_index_map[$row['subj']]]['items'],array('name'=>$row['topicname']));
    }
    close_analysis_db($link);
    return $topics_over;
}

function get_ptm_records($username){
    $link = connect_analysis_db();
    $qry = <<<SQL
    select sch.date as date, sch.event as event 
    from schedule as sch 
    join (select centre as centre from userinfo where userid=$username) as user 
    join (select id, centreid from ttbatches where name like 'Student Improvement Session') as tt
    on user.centre = tt.centreid and sch.batchid = tt.id
    where sch.event like '%PTM%' order by sch.date asc; 
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