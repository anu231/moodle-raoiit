<?php
//does a select sync of only the branch and center details only
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->libdir.'/raolib.php');
global $DB;

$params = cli_get_params(array(
    'batchfile' => false,
    'fields' => false
));

$filename = $params[0]['batchfile'];
$fields = explode(',',$params[0]['fields']);

$cnt = 1;
if (($handle = fopen($filename, "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $username = trim($data[0]);
        $field_data = array();
        for ($i=0; $i<count($fields); $i++){
            array_push($field_data, array('field'=>$fields[$i], 'val'=>trim($data[$i+1])));
        }
        echo "$cnt. $username | ".$field_data[0]['val'].PHP_EOL;
        if ($username == NULL || $username == ''){
            continue;
        }
        $user = $DB->get_record('user',array('username'=>$username));
        if (empty($user)){
            continue;
        }
        for($i=0; $i< count($field_data); $i++){
            $fd = $field_data[$i];
            if ($fd['val'] != ''){
                set_profile_field($fd['field'], $user->id, $fd['val']);
            }
        }
        $cnt = $cnt+1;
    }
    fclose($handle);
}