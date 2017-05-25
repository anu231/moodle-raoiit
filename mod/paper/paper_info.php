<?php

require_once('../../config.php');
require_once('locallib.php');
$id = required_param('id', PARAM_INT);
require_login();
echo json_encode((array)paper_remote_fetch_info($id));

