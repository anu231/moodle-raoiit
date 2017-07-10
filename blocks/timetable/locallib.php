<?php

function get_subject_name($shortname){
$subj_map = array(
		'p'=>'Physics',
		'c'=>'Chemistry',
		'm'=>'Maths',
		'z'=>'Zoology',
		'b'=>'Botany'
    );
    if (!array_key_exists($shortname, $subj_map)){
        return 'Unknown Subject';
    } else {
        return $subj_map[$shortname];
    }

}
function x_week_range(&$start_date, &$end_date, $date) {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 1) ? $ts : strtotime('last monday', $ts);
    $start_date = date('Y-m-d', $start);
    $end_date = date('Y-m-d', strtotime('next saturday', $start));
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



function get_student_batches($link, $username){
    //assumes the db connections have already been made
    $batches = '';
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
    $qry_center = "select id from ttbatches where name like 'Student Improvement Session' and centreid=".$row['centre'];
    $res_center = $link->query($qry_center);
    if (!$res_center){
        return $batches;
    }
    $row_center = $res_center->fetch_assoc();
    if (!$row_center){
        return $batches;
    } else{
        return $batches.','.$row_center['id'];
    }
}

function get_timetable($start_date,$end_date,$username){
    /*$subj_map = array(
		'p'=>'Physics',
		'c'=>'Chemistry',
		'm'=>'Maths',
		'z'=>'Zoology',
		'b'=>'Botany'
    );*/
    $link = connect_analysis_db();
    $batches = get_student_batches($link, $username);
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
        $tmp['fancydate'] = date('Y-m-d',strtotime($lecture['date']));
        //$tmp['date'] = "{$lecture['d']}-{$lecture['m']}-{$lecture['y']}";
        $tmp['starttime'] = date('H:i',strtotime($lecture['from']));//$lecture['sh'].':'.$lecture['sm'];
        $tmp['endtime'] = date('H:i',strtotime($lecture['to']));//$lecture['eh'].':'.$lecture['em'];
        //$tmp['teacher'] = isset($lecture['sn']) ? $lecture['sn'] : '';
        if ($lecture['istest']=='0'){
            $tmp['teacher'] = $lecture['shortname'];
            $tmp['subject'] = get_subject_name($lecture['subject']);
            $tmp['topicname'] = $lecture['topicname'];
        } else{
            $tmp['notes'] = $lecture['testtype'].'-'.$lecture['testnum'].' '.$lecture['event'];
        }
        $tmp['cancelclass'] = $lecture['iscancel'] == '1' ? 'cancelled' : '';
        $tmp['batch'] = $lecture['centrename'].' - '.$lecture['batchname'];
        //    $day[] = $tmp;
        //}
        if (!array_key_exists($tmp['fancydate'],$days_added_map)){
            $days_added_map[$tmp['fancydate']] = $index;
            $processed_lectures[$index] = array();
            $processed_lectures[$index]['items'] = array();
            $processed_lectures[$index]['fancydate'] = $tmp['fancydate'];
            $index++;
        }
        //$processed_lectures[$days_added_map]['fancydate'] = $tmp['fancydate'];
        //$processed_lectures[$index]['items'] = $day;
        array_push($processed_lectures[$days_added_map[$tmp['fancydate']]]['items'],$tmp);
    }
    close_analysis_db($link);
    return $processed_lectures;
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
    $qry = "select sch.date as date, sch.event as event from schedule as sch join (select ttbatchid as ttbatchid, extbatchid as extbatchid from userinfo where userid=".$username.") as user on user.ttbatchid=sch.batchid or user.extbatchid=sch.batchid where sch.event like '%PTM%' order by sch.date asc"; 
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
