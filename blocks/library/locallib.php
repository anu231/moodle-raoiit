<?php
defined('MOODLE_INTERNAL') || die();

function compute_date_diff($issue_date,$return_date){
    global $DB;
    $issue_date=date_create($issue_date);
    $return_date = date_create($return_date);
    $diff=date_diff($issue_date,$return_date);
    return $diff->format("%a");
}

function compute_return_date($issue_date){
    $return_days = get_config('library','issuedays');
    $date = date_create($issue_date);
    date_add($date, date_interval_create_from_date_string($return_days.' days'));
    return date_format($date, 'Y-m-d'); // limit date is 7 times greater than issue date //
}
function issue_date(){
    $issue_date = date('Y-m-d');
    return $issue_date;
}
 function get_all_books(){
    global $CFG,$USER,$DB;
    $book = $DB->get_records('lib_bookmaster', array("status"=>1,"barcode"=>357121072993705));
    return $book;
}
/*
function get_instance(){
    global $DB;
    $instance = $DB->get_record('block_instances', array("blockname"=>"library"));
    return $instance_id = $instance->id;
}
*/

/*function is_branch_admin(){
    global $USER, $DB;
    $course_active = get_config('library','manager_course');
    $course_active = $DB->get_record('course',array('shortname'=>$course_active));
    //check if user is enrolled in the course
    $context = context_course::instance($course_active->id);
    if (is_enrolled($context, $USER->id, '', true)){
        return true;
    } 
    return false;
}*/

function get_centers_book(){
    global $DB;
    //$get_books = $DB->get_records('lib_bookmaster', array("status"=>1,"branch"=>get_user_center(),"is_scanned"=>0));
    //return $get_books;


    $get_books = <<<SQL
    select DISTINCT bookid,id,name from {lib_bookmaster}  where status = 1 AND branch =? AND is_scanned = 0 ORDER BY bookid DESC
SQL;
   $get_books_records = $DB->get_records_sql($get_books,array(get_user_center()));
   //var_dump($get_books_records);
   return $get_books_records;
}


function convert_std_to_array_bookid($tl){
    $ts_arr = Array();
    foreach($tl as $t){
        $ts_arr[$t->id] = $t->bookid." - ".$t->name;
    }
    return $ts_arr;
}

function is_secondary_user(){
    global $USER;
    if ($USER->username == 'abhishek.pawar'){
        return True;
    } else {
        return False;
    }
}