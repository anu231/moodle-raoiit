<?php
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once('../locallib.php');

sync_dtp_topics();
