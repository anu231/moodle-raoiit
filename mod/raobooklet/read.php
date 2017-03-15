<?php

/**
* read.php
*
* The script serves pdf pages.
*
* Required parameters:
* @param instanceid - of the pdf
* Optional parameters:
* @param page - Page number.
*
*/

global $CFG;
require_once('../../config.php');

// Variable setup
try {
    $bookletid = required_param('bookletid', PARAM_INT);
    $page = optional_param('page', -1, PARAM_INT); // page = 0 for metadata
    $get_items = optional_param('get_items', 0, PARAM_INT); // get the photoswipe items
} catch (Exception $e) {
    echo $e;
    die;
}

// Check login
if( ! $USER->id >= 2 ){ // 2 is admin.
    echo "Please Log in to view the contents"; // TODO redirect to the login page.
    die;
}


if ( $page == -1 && $get_items == 0 ) {
    require('templates/read.html');
} else if( $get_items != 0 ) {
    // Send get_items array
    $path = "$CFG->dataroot/raobooklets/".$bookletid.'/';
    $images = glob($path.'*');
    $json = array();
    foreach ($images as $image) {
        $exp = explode('/', $image);
        $number = explode('.', $exp[6])[0];
        $json[$number] = "$CFG->wwwroot/mod/raobooklet/read.php?bookletid=$bookletid&page=".$number;
    }

    header('Content-Type: application/json');
    echo json_encode($json);
} else if ( $page != -1 ) {
    $path = "$CFG->dataroot/raobooklets/".$bookletid.'/';
    $page = fopen($path.$page.'.jpg', 'r');
    header('Content-Type: image/jpeg');
    fpassthru($page);
}
