<?php
//fetches book info and returns json for it
require_once('../../config.php');
require_once('locallib.php');
require_login();

/**
gets the info for the book specified
**/
global $DB,$USER;
$batch = required_param('batch_id',PARAM_TEXT);
$book_barcode = $DB->get_record('lib_bookmaster', array("status"=>1,"id"=>$batch));
$book_barcode_data=json_encode($book_barcode);
echo $book_barcode_data;
// get barcode from book library //


//reverts a json of the entire info about the book, also checks if the booknis currently issued
