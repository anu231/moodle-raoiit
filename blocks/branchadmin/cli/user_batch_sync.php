<?php
//does a select sync of only the branch and center details only
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->libdir.'/raolib.php');
global $DB;

$params = cli_get_params(array(
    'batchfile' => false
));

$filename = $params[0]['batchfile'];

$cnt = 1;
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $username = trim($data[0]);
        $ttbatch = trim($data[1]);
        $extbatch = trim($data[2]);
        echo "$cnt. $username|$ttbatch|$extbatch".PHP_EOL;
        if ($username == NULL || $username == ''){
            continue;
        }
        $user = $DB->get_record('user',array('username'=>$username));
        if (empty($user)){
            continue;
        }
        if ($ttbatch != '' && $ttbatch != '0'){
            set_profile_field('batch', $user->id, $ttbatch);
        }
        if ($extbatch != '' && $extbatch != '0'){
            set_profile_field('extbatchid', $user->id, $extbatch);
        }
        $cnt = $cnt+1;
    }
    fclose($handle);
}