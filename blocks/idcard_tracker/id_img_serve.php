<?php

require_once('../../config.php');

global $CFG;
require_login();

$username = required_param('username', PARAM_INT);
$idcard_image = $CFG->id_card_image.$username;

if(!file_exists($idcard_image)){
    return;
}

$img = fopen($idcard_image, 'r');
header('Content-Type: image/jpeg');
fpassthru($img);