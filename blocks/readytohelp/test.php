<?php

//testing the access api
require_once('../../config.php');
require_once('locallib.php');

$data = new stdClass;
$data->category = '1';
$data->timecreated = time();
$data->subject = 'halp! i very angry ';
$data->description = 'pls halp! siR, I canot see ma paperz on tezt portl. Plz helP me nuw! I want return ma monee';
$data->emails = array('akshay.handrale@raoiit.com');
send_grievance_dept_emails(1, $data);
echo "done";
