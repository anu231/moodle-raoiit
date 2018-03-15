<?php

require(__DIR__.'/../../../config.php');

$username = required_param('username',PARAM_TEXT);

$dbauth = get_auth_plugin('db');
echo $dbauth->sync_user($username);
