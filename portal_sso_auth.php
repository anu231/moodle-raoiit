<?php

require_once('config.php');
global $USER, $CFG;

$secret = $CFG->secret_key;
$nonce = base64_decode($_GET['nonce']);

if (isset($USER) && isset($USER->id) && intval($USER->id) > 0 ){
    //eecho 'User logged in';
    $st = $USER->id.$nonce;
	#echo $st.'<br>';
	$hash_msg = hash_hmac('sha256',$st,$secret);
	#echo $hash_msg.'<br>';
	$st_encoded = base64_encode($st);
	$hash_encoded = base64_encode($hash_msg);
	#echo "http://192.168.1.19:8000/sso_sign_in?sso_sig=".$st_encoded.'&sso_hash='.$hash_encoded.'&nonce='.$_GET['nonce'];
	header("Location:".$CFG->django_server."sso_sign_in?sso_sig=".$st_encoded.'&sso_hash='.$hash_encoded.'&nonce='.$_GET['nonce']);
} else {
    redirect(get_login_url());
}

?>