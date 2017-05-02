<?php

require_once('config.php');
global $USER, $CFG;

$secret = 'd836444a9e4084d5b224a60c208dce14';//$CFG->discussions_secret_key;
$sso = urldecode($_GET['sso']);
$sig = urldecode($_GET['sig']);
$sso_hash = hash_hmac('sha256',$sso,$secret);

if ($sso_hash!=$sig){
	echo 'Hash does not match';
	exit;
}
$nonce = base64_decode($sso);
$data = $nonce.'&name='.$USER->username.'&username='.$USER->username.'&email='.$USER->email.'&external_id='.$USER->username;
//echo $data;
$data_base64 = base64_encode($data);
$sig_out = hash_hmac('sha256',$data_base64,$secret);
$sso_out = urlencode($data_base64); 
header("Location:".$CFG->discourse_url."sso_login?sso=".$sso_out.'&sig='.$sig_out);
exit;