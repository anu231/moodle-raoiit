<?php

require(__DIR__.'/../../../config.php');

//$allowed_ips = array('203.123.46.194', '144.168.165.242', '104.227.244.29');

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
echo get_client_ip().'<br>';
if (!array_key_exists(get_client_ip(), $allowed_ips)){
    echo 'ACCESS DENIED';
    //exit;
}

$username = required_param('username',PARAM_TEXT);

$dbauth = get_auth_plugin('db');
echo $dbauth->sync_user($username);
