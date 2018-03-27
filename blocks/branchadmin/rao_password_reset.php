<?php
require(__DIR__.'/../../config.php');
global $CFG;
require_once($CFG->libdir.'/raolib.php');
if ((isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR'] == '203.123.46.194' || $_SERVER['REMOTE_ADDR'] == '192.168.1.19'))
 || (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] == '203.123.46.194')){
    //good
} else {
    echo 'Not Allowed from this IP. Access Denied';
    exit;
}
$passwd = '';
$userid = $_GET['userid'];


$auth = $_GET['auth'];

if ($auth != 'v1Bdyp'){
    echo 'Wrong Auth';
    exit;
}
if ($userid==null){
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

if (isset($_GET['passwd'])){
    $passwd = $_GET['passwd'];
} else {
    $passwd = get_rao_password($user);
    //$passwd = $dob['birthdate'];
}

$hashedpassword = hash_internal_user_password($passwd);

$DB->set_field('user', 'password', $hashedpassword, array('id'=>$user->id));

echo "Password changed\n".$passwd;

exit(0); // 0 means success.
