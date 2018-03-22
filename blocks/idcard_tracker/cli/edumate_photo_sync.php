<?php
define('CLI_SCRIPT', true);
require(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/clilib.php');
require_once($CFG->dirroot.'/user/profile/lib.php');
require_once("$CFG->libdir/gdlib.php");
require_once("$CFG->libdir/formslib.php");

$params = cli_get_params(array(
    'folder' => '/var/www/moodledata/admission_portal_images/'
));

echo $params[0]['folder'].PHP_EOL;
$foldername = $params[0]['folder'];

$csv = array();
$file = fopen('adm_admissions.csv', 'r');

while (($result = fgetcsv($file)) !== false)
{
$csv[] = $result;
}

fclose($file);


$count = count($csv);


for($i=0;$i<$count;$i++)
{
//echo 'Inserting data of roll no-'.$csv[$i][0].PHP_EOL;
GLOBAL $USER,$DB,$CFG;
$user = $DB->get_record('user',array('username'=>$csv[$i][0]));
profile_load_data($user);
$student_idcard = new stdClass(); 
$student_idcard ->student_username = $user->username;
$student_idcard ->student_fullname = $user->firstname." ".$user->lastname;
$student_idcard->profile_pic = $CFG->wwwroot.'/blocks/idcard_tracker/id_img_serve.php?username='.$user->username;
$student_idcard ->branch = 5;  //$user->profile_field_center;
$student_idcard ->student_course = $user->profile_field_coursetypename['text'];
$student_idcard ->student_mobile_number = $user->profile_field_studentmobile;
$student_idcard ->student_targetyear = '2019';
$student_idcard ->idcard_valid = '7-June-2019';
$student_idcard ->idcard_status = 0;
$DB->insert_record('student_idcard_submit', $student_idcard);
//
$uploadedFile = $foldername.$csv[$i][1].'.jpg';
$destination =  $CFG->id_card_image.$csv[$i][0];
copy($uploadedFile, $destination);
//unlink($uploadedFile);
break;
}

