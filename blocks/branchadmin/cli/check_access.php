<?php
if ((isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == '203.123.46.194' || $_SERVER['REMOTE_ADDR'] == '192.168.1.19'))
    || (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == '203.123.46.194')){
    require(__DIR__.'/../../../config.php');
    require_once($CFG->libdir.'/moodlelib.php');
    global $DB;
    $username = $_GET['username'];
    $user = $DB->get_record('user',array('username'=>$username));
    if ($user){
        if ($user->lastaccess == 0){
            echo 0;
            exit;
        } else {
            echo 1;
            exit;
        }
    }else {
        echo -1;
        exit;
    }
}else {
    echo 'ACCESS DENIED';
    exit;
}
