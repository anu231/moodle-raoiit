<?php

/**
 * read.php
 *
 * The script serves pdf pages.
 *
 * Required parameters:
 *  @param bookletid - of the pdf
 * Optional parameters:
 *  @param int $page - Page number.
 *  @param int $get_items - if set (>=1) will return a json of {pagenumber: imagesurl}
 * 
 * These are the 3 possible return types
 * @return array $response_json 
    {
        status: 'ok/error/authError/noImageError',
        images: {
            "0": 'image url 1',
            "1": 'image url 2',
        }
    }

 * @return html template
 * @return image.jpg
 */

global $CFG, $USER, $PAGE;
require_once('../../config.php');

$PAGE->set_url('/mod/raobooklet/read_ws.php');
try {
    $bookletid = required_param('id', PARAM_INT);
    $ts = required_param('ts', PARAM_INT);
    $hash = required_param('hash', PARAM_TEXT);
    $page = optional_param('page', -1, PARAM_INT); // page = 0 for metadata
} catch (Exception $e) {
    // Send error status
    //echo $e;
    //$json['status'] = 'error';
    //header('Content-Type: application/json');
    //echo json_encode($json);
    http_response_code(400);
    die;
}

$server_hash = md5($CFG->custom_salt.$bookletid.$ts);
// Check login TODO CHECK COURSE LOGIN TOO
// Send authError
if ($server_hash != $hash){
    http_response_code(401);
    die;
}


if ( $page == -1) {
    http_response_code(400);
    die;   
} else {
    // Send image of $page
    $path = "$CFG->dataroot/raobooklets/".$bookletid.'/';
    $f = $path.$page.'.jpg';
    if (file_exists($f)){
        $page = fopen($f, 'r');
        header('Content-Type: image/jpeg');
        fpassthru($page);
    }else {
        http_response_code(400);
        die;
    }
}
