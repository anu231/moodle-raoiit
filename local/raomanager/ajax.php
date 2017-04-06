<?php

// Example query
// http://edumate.raoiit.com/local/raomanager/ajax.php?app=admin&q=smiley
// All usernames containing 'smiley' will be returned

require_once('../../config.php');

$app = optional_param('app', '', PARAM_RAW);
$q = optional_param('q', '', PARAM_RAW);

global $DB, $CFG, $USER;

require_login();

$json = array(
    'result' => array(
        'id' => 0,
        'name' => ''
    )
);

if(strlen($q) < 3) { // No Queries less than 3 chars long
    header('Content-Type: application/json');
    echo json_encode(array());
    die;
}

switch ($app) {
    case 'admin':
        $results = $DB->get_records_sql("SELECT id, username FROM mdl_user WHERE username LIKE '%$q%'");
        header('Content-Type: application/json');
        echo json_encode($results);
        break;
    default:
        break;
}
