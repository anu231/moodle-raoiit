<?php

/*$host = 'localhost';
$username = 'root';
$pass = 'v1Bdyp';
$db = 'analysis';
*/

$paperid = NULL;
$paperid2 = NULL;
$target = NULL;
$batch = NULL;
$total_students = NULL;
//paper-1
$n_phy1 = NULL;
$n_chem1 = NULL;
$n_maths1 = NULL;
//paper-2
$n_phy2 = NULL;
$n_chem2 = NULL;
$n_maths2 = NULL;

$host = 'analysis.raoiit.com';
$username = 'analysis_edumrao';
$pass = 'REduMa@2017#$123';
$db = 'analysis_riit_analysis';


$paperid = 1496;
$paperid2 = 0;
$target = 2019;
$batch = "2";
$total_students = '1230';
//paper-1
$n_phy1 = 20;
$n_chem1 = 20;
$n_maths1 = 20;
//paper-2
$n_phy2 = 26;
$n_chem2 = 26;
$n_maths2 = 26;


$paper_info_cURL = "https://portal.raoiit.com/student/pinfo?";
$spr_cURL = "https://portal.raoiit.com/student/spr?auth=v1Bdyp&";
/*$email_img_link = "https://edumate.raoiit.com/student_progress_report";
$email_link = "https://edumate.raoiit.com/student_progress_report/spr_v2.html";*/


$email_img_link = "http://192.168.1.20/pd/student_progress_report";
$email_link = "http://192.168.1.20/pd/student_progress_report/spr_v2.html";

$adm_host = '104.227.244.29';
$adm_username = 'admissio_riitedu';
$adm_pass = '1ca79770547b101f0f558b3847b67403';
$adm_db = 'admissio_riitadmissions';
?>