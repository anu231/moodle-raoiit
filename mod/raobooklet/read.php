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

$PAGE->set_url('/mod/raobooklet/read.php');

try {
    $bookletid = required_param('bookletid', PARAM_INT);
    $page = optional_param('page', -1, PARAM_INT); // page = 0 for metadata
    $get_items = optional_param('get_items', 0, PARAM_INT); // get the photoswipe items
} catch (Exception $e) {
    // Send error status
    echo $e;
    $json['status'] = 'error';
    header('Content-Type: application/json');
    echo json_encode($json);
    redirect(new moodle_url('/'));
    die;
}

// Check login TODO CHECK COURSE LOGIN TOO
// Send authError. The app opens a new tab for login
if( ! isLoggedIn() ){
    echo "Please Log in to view the contents";
    $json['status'] = 'authError';
    header('Content-Type: application/json');
    echo json_encode(require_login());
    die;
}


if ( $page == -1 && $get_items == 0 ) {
    // send the html template
    require('templates/read.html');
} 
else if( $get_items != 0 ) {
    // Send get_items array
    $response_json = array('status' => '', 'images' => array());
    $path = "$CFG->dataroot/raobooklets/".$bookletid.'/';
    $images = glob($path.'*');
    if( count($images) == 0 ){
        $response_json['status'] = 'noImageError';
        header('Content-Type: application/json');
        echo json_encode($response_json);
    }
    else {
        foreach ($images as $image) {
            $exp = explode('/', $image);
            $number = explode('.', $exp[count($exp)-1])[0];
            $response_json['images'][$number] = "$CFG->wwwroot/mod/raobooklet/read.php?bookletid=$bookletid&page=".$number;
        }
        $response_json['status'] = 'ok';
        header('Content-Type: application/json');
        echo json_encode($response_json);
    }
}
else if ( $page != -1 ) {
    // Send image of $page
    $path = "$CFG->dataroot/raobooklets/".$bookletid.'/';
    $page = fopen($path.$page.'.jpg', 'r');
    header('Content-Type: image/jpeg');
    fpassthru($page);
}
