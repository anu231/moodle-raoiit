<?php
require_once('../../config.php');
require_once('info_form.php');
require_once('locallib.php');
require_once('../../vendor/autoload.php');
global $USER;
$username = $USER->username;
$PAGE->set_url('/blocks/branchadmin/send_email.php');
if ($username=='admin')//saurabhadmin
{
    $PAGE->set_pagelayout('standard');
    $PAGE->set_heading('Send SMS Notifications');
    echo $OUTPUT->header();
    $mform = new send_email();
    if ($data = $mform->get_data()){
     $str= implode(",",$data->course);
        $email_subject = $data->email_subject;
        $email_content = $data->email_content;
        //r.roleid =3
    $sql = <<<SQL
SELECT u.firstname, u.lastname, u.email, c.id,c.fullname
FROM mdl_user u, mdl_role_assignments r, mdl_context cx, mdl_course c
WHERE u.id = r.userid
AND r.contextid = cx.id
AND cx.instanceid = c.id
AND r.roleid =5
AND cx.contextlevel =50
AND c.id = ?
SQL;
 $records = $DB->get_records_sql($sql, array($str));
 $array = array();
 foreach ($records as $value) 
    $array[] = $value->email;
     echo "<pre>";
   print_r ($array);
  echo count ($array);
  //
        $student_name = 'Abhishek Pawar';
		$student_email = 'abhishek.pawar@raoiit.com';
	    send_email_centres($student_name,$student_email);
   

    } else {
        $mform->display();
    }
    echo $OUTPUT->footer();
}
else
{
$PAGE->set_pagelayout('standard');

echo $OUTPUT->header();
GLOBAL $USER;
$firstname=$USER->firstname;
$lastname= $USER->lastname;
 $fullname=$firstname." ".$lastname;
echo "<h5>Dear, $fullname </h5>";
echo "<br>";
echo "<h5>You are not Authorised Person</h5>";
echo "<a href='$CFG->wwwroot'>Back to Page</a>";

echo $OUTPUT->footer();
}
?>