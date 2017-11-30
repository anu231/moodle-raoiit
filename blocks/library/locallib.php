<?php
defined('MOODLE_INTERNAL') || die();
require_once('../../config.php');
require_once('library_issue.php');
function compute_fine($limit_date,$return_date){
    global $DB;
    $limit_date=date_create($limit_date);
    $return_date = date_create($return_date);
    $diff=date_diff($limit_date,$return_date);
    $date_diff1=$diff->format("%R%a days");
    return $date_diff1;
}

function limit_date(){
    $nextWeek = time() + (7 * 24 * 60 * 60);
    $limit_date = date('Y-m-d', $nextWeek); // limit date is 7 times greater than issue date //
    return $limit_date;
}
function issue_date(){
    $issue_date = date('Y-m-d');
    return $issue_date;
}
 function get_all_books(){
    global $CFG,$USER,$DB;
    $book = $DB->get_records('lib_bookmaster', array("status"=>1,"barcode"=>357121072993705));
    print_r($book);
            return $book;
}
