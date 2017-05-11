<?php

/**
 * Generate a random string, using a cryptographically secure 
 * pseudorandom number generator (random_int)
 * 
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 * 
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}

require_once('config.php');
global $USER, $CFG;

$secret = $CFG->secret_key;
$nonce = random_str(32);

if (isset($USER) && isset($USER->id) && intval($USER->id) > 0 ){
    $st = $USER->username.$nonce;
	$hash_msg = hash_hmac('sha256',$st,$secret);
	$st_encoded = base64_encode($st);
	$hash_encoded = base64_encode($hash_msg);
	$message = array();
	$message['sig'] = $st_encoded;
	$message['hash'] = $hash_encoded; 
	$message['nonce'] = $nonce;
	//echo json_encode($message);
	header('Location:'.$CFG->django_server.'sso_moodle?sig='.$st_encoded.'&hash='.$hash_encoded.'&nonce='.$nonce);
	exit;
} else {
	echo 'Invalid Login';
	exit;
}

?>