<?php
//fetches book info and returns json for it
require_once('../../config.php');
require_once('locallib.php');
require_login();

/**
gets the info for the book specified
**/
global $DB,$USER;
if (!is_branch_admin()){
    echo -1;    
} else {
    $book_id = required_param('id',PARAM_TEXT);
    $book = $DB->get_record('lib_bookmaster', array("status"=>1,"id"=>$book_id));
    $book_data=json_encode($book);
    echo $book_data;
    // get barcode from book library //


    //reverts a json of the entire info about the book, also checks if the booknis currently issued
}
