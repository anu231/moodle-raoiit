<?php
//fetches book info and returns json for it
require_once('../../config.php');
require_once("{$CFG->libdir}/raolib.php");
require_once('../branchadmin/locallib.php');
require_once('locallib.php');
require_login();

global $DB,$USER;
$book_id = required_param('book_id',PARAM_TEXT);
$book_barcode = $DB->get_record('lib_bookmaster', array("status"=>1,"id"=>$book_id));
$book_barcode_data=json_encode($book_barcode);
echo $book_barcode_data;
