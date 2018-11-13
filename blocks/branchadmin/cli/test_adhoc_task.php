<?php
//does a select sync of only the branch and center details only
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/cronlib.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->dirroot.'/vendor/autoload.php');
global $DB;

$task = \core\task\manager::get_next_adhoc_task(time());
$lock = $task->get_lock();
$lock->release();
cron_run_inner_adhoc_task($task);

unset($task);
