<?php
function get_tiny_url($url)  {  
  $ch = curl_init();  
  $timeout = 5;  
  curl_setopt($ch,CURLOPT_URL,'http://tinyurl.com/api-create.php?url='.$url);  
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
  $data = curl_exec($ch);  
  curl_close($ch);  
  return $data;  
}

//test it out!
$new_url = get_tiny_url('https://edumate.raoiit.com/student_progress_report/spr.html?userid=917536&paperid=1499&hashdata=9c930fe8daf13a9a75bdab3259bb5c8d');

//returns http://tinyurl.com/65gqpp
echo $new_url
?>
