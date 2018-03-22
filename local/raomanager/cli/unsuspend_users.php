<?php
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');

$params = cli_get_params(array(
    'file' => false
));

echo $params[0]['file'].PHP_EOL;
$filename = $params[0]['file'];

$myfile = fopen($filename, "r") or die("Unable to open file!");
// Output one line until end-of-file
global $DB;
while(!feof($myfile)) {
  $username = trim(fgets($myfile));
  $user = $DB->get_record('user',array('username'=>$username));
  if ($user->suspended == 1){
      $user->suspended = 0;
      $DB->update_record('user',$user);
      echo 'Unsuspended :'.$username.PHP_EOL;
  }
}
fclose($myfile);