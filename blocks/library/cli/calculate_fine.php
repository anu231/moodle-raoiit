<?php

//define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once(__DIR__.'/../locallib.php');

/*
Goes through all the issue records and updates the fine entries in the fine records table
*/
global $DB;

$return_limit = (int)get_config('library', 'issuedays');
$fine_per_day = (int)get_config('library','fine');
$today = date('Y-m-d');
//get all the issue records whose fine entries have been done
$sql = <<<SQL
    select 
            issue_rec.id as issue_id,
            issue_rec.student_username as stud_username,
            issue_rec.issue_date as issue_date,
            issue_rec.return_date as return_date,
            issue_rec.status as issue_status,
            issue_rec.bookid as bookid,
            issue_rec.branch_id as branch_id,
            issue_rec.branch_issuer as branch_issuer,
            fine_rec.id as fine_id,
            fine_rec.amount as amount,
            fine_rec.paid as paid,
            fine_rec.remark as remark
     from {lib_issue_record} as issue_rec 
     left outer join 
     (SELECT * 
        FROM {lib_fine_record}
        WHERE paid = 0)
     as fine_rec on issue_rec.id=fine_rec.issue_id
    where ? > DATE_ADD(issue_rec.issue_date, INTERVAL $return_limit DAY)
SQL;
$fine_entries = $DB->get_records_sql($sql,array($today, $return_limit));

foreach($fine_entries as $entry){
    //computing the fine amount
    $fine = $entry->amount;
    $last_day = NULL;
    if ($entry->issue_status == '0'){
        //checked
        //book has not been returned
        $last_day = $today;
    } else {
        if ($entry->fine_id == NULL){
            //checked
            //fine entry has not been generated
            $last_day = $entry->return_date;
        }
    }
    $days = compute_date_diff($entry->issue_date, $last_day);
    $fine = $days*$fine_per_day;
    //if fine entry is already generated with book  retuned , no need to process it, it happens when $last_day == NULL
    if ($last_day != NULL){
        //update the fine records
        if ($entry->fine_id == NULL){
            //create new fine record
            $fine_entry = new stdClass();
            $fine_entry->issue_id = $entry->issue_id;
            $fine_entry->bookid = $entry->bookid;
            $fine_entry->branch_id = $entry->branch_id;
            $fine_entry->student_username = $entry->stud_username;
            $fine_entry->amount = $fine;
            $fine_entry->book_status = $entry->issue_status;
            $fine_entry->branch_issuer = $entry->branch_issuer;
            if ($entry->status == '1'){
                $fine_entry->return_date = $entry->return_date;
            }
            //save this record
            echo 'Creating new Fine Record issue_id :'.$entry->issue_id.PHP_EOL;
            //print_r($fine_entry);
            $DB->insert_record('lib_fine_record', $fine_entry);
        } else {
            if ($entry->issue_status == '0') {
                //need to update the fine status
                //as book has not been returned
                $fine_rec = new stdClass();
                $fine_rec->id = $entry->fine_id;
                $fine_rec->amount = $fine;
                $DB->update_record('lib_fine_record',$fine_rec);
                echo 'Updating Fine Record issue_id :'.$entry->issue_id.PHP_EOL;
                //print_r($fine_rec);
            }//no need to update fine record for a returned book as it would have already been done at time of return
        }
    }    
}