<?php
require(__DIR__.'/../../config.php');

if ((isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == '203.123.46.194' || $_SERVER['REMOTE_ADDR'] == '192.168.1.19'))
 || (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == '203.123.46.194')){
    //good
} else {
    echo 'Not Allowed from this IP. Access Denied';
    exit;
}

$userid = $_GET['userid'];
$passwd = $_GET['passwd'];
$auth = $_GET['auth'];

if ($auth != 'v1Bdyp'){
    echo 'Wrong Auth';
    exit;
}
if ($passwd==null || $userid==null){
    echo 'No Info provided';
    exit;
}
if (!is_numeric($userid)){
    echo 'Only Student passwords can be reset using this';
    exit;
}
global $DB;
//get the user 

if (!$user = $DB->get_record('user',array('username'=>$userid))){
    echo 'No Such user exists';
    exit;
}


$hashedpassword = hash_internal_user_password($passwd);

$DB->set_field('user', 'password', $hashedpassword, array('id'=>$user->id));

echo "Password changed\n";

exit(0); // 0 means success.
