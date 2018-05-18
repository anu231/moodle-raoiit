<?php

define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');
require_once(__DIR__.'/../locallib.php');

global $CFG, $DB;

$params = cli_get_params(array(
    'file' => false
));

echo $params[0]['file'].PHP_EOL;
$filename = $params[0]['file'];
$cnt = 1;
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //print_r($data);
        $bookid = $data[0];
        $analysis_center = $data[1];
        //get the book entry in edumate
        $book_entry = $DB->get_record('lib_bookmaster', array('bookid' => $bookid));
        if (empty($book_entry)){
            continue;
        }
        //check if the centers are the same in analysis and edumate
        if ($analysis_center == $book_entry->branch){
            continue;
        } else {
            //there is a difference
            //update the branch in edumate to analysis id
            echo $cnt.'. ';
            $cnt = $cnt+1;
            echo $book_entry->bookid.'-'.$analysis_center.PHP_EOL;
            $book_entry->branch = $analysis_center;
            $DB->update_record('lib_bookmaster', $book_entry);
        }
        //break;
    }
    fclose($handle);
}
