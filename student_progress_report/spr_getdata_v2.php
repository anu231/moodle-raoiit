<?php

error_reporting(0);
$username=NULL;
$id=NULL;
$userid=NULL;
$paperid=NULL;
$hash=NULL;


include('db_connect.php');
include('config.php');
/*
    function for get all the active_user_info form batch
    parameters - 
    return - $user (userinfo of active batches)  
    summary - takes active batch info & returns all active student of that batch.
*/
function get_user_info($adm_link_id){
	  $userid=$_GET['userid'];
	  $batch=NULL;
	
    $user = array();
   //$sql="SELECT u.*,c.name as centrename FROM userinfo as u JOIN centreinfo as c ON u.centre = c.id WHERE userid='$userid'";
   $sql="SELECT a.admid,a.rollnumber,a.stufname,a.stulname,a.centre,a.studentmobile,a.fathermobile,a.mothermobile,a.studentemail,a.fatheremail,a.motheremail,a.coursename,c.coursetype,c.coursename,c.batch FROM `adm_admissions` AS a  INNER JOIN `adm_courses` AS c ON a.coursename=c.id WHERE a.rollnumber='$userid' AND a.status=1";

   $result=mysqli_query($adm_link_id,$sql);
  
    if(mysqli_num_rows($result) > 0 ){
        while ($row=mysqli_fetch_assoc($result)){
            $user= $row;
            	if($row['batch']==0 || $row['batch']==1){
		        	$batch='JEE Main+Advanced';
		        }
		        else if($row['batch']==9 || $row['batch']==10){
		        	$batch='JEE Main';
		        }
		        else if($row['batch']==2 || $row['batch']==3){
		        	$batch='Medical-UG';
		        }
		        else if($row['batch']==4){
		        	$batch='Rao Start Smart';
		        }
		    
		    }
		        $user['course'] = $batch;
    }

    return $user;
}


/*
    function for get Paper info of paperid(pid)
    parameters - 
    return - $paper_data (paperinfo of paperid)  
    summary - Takes paperid(pid) in cURL & returns all the info of that paper id
*/


function paper_info(){
	include('config.php');
	$paper_data = array();
	$pid=$_GET['paperid'];
	$paper_info = $paper_info_cURL."pid=$pid";   // cURL for getting test paper info
	//$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
	$ch1 = curl_init();
	curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch1, CURLOPT_URL,$paper_info);
	//curl_setopt($ch, CURLOPT_USERAGENT, $agent);
	$paper_info=curl_exec($ch1);
	curl_close($ch1);
	//var_dump(json_decode($result,true));
	$paper_data = json_decode($paper_info, true);
  return($paper_data);
}





/*
    function for get test report of MAX_MARKS student with perticular paperid(pid) in cURL
    parameters - 
    return - $spr_data (MAX_MARKS student test performance data)  
    summary - Returns MAX_MARKS student test performance data of that paperid(pid)
*/

function max_spr(){
  include('config.php');
  $max_spr = array();
  $pid=$_GET['paperid'];
  $max_spr = $spr_cURL."username=999991&pid=$pid";    // cURL to get the max_spr
  //$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

  $ch1 = curl_init();
  curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch1, CURLOPT_URL,$max_spr);
  //curl_setopt($ch, CURLOPT_USERAGENT, $agent);
  $response1=curl_exec($ch1);
  curl_close($ch1);
  //var_dump(json_decode($result,true));
  $res_max_spr = json_decode($response1, true);
  return($res_max_spr);

  
   
}


/*
    function for get test report of student with perticular paperid(pid) in cURL
    parameters - $paper_data(paper info of perticular paper id)
    return - $spr_data (student test performance data)  
    summary - Takes paper_data(paper info) & returns student test performance data of that paper
*/

function user_test_report($paper_data,$max_spr){
  include('config.php');
  //$spr_data = array();
  $userid=$_GET['userid'];
  $pid=$_GET['paperid'];
  $hashdata=$_GET['hashdata'];
  
   $loginUrl = $spr_cURL."username=$userid&pid=$pid";   // cURL to get student performance info for perticular paper_id
  //$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';
//exit;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$loginUrl);
  //curl_setopt($ch, CURLOPT_USERAGENT, $agent);
  $response=curl_exec($ch);
  curl_close($ch);
  $spr_data = json_decode($response, true);
//echo "<pre>";
//print_r($spr_data);
//exit;
  function get_subject($paper_data)
  {
    $test_subjects = array();
    $sub_data = array( "p"=>"phy", "c"=>"chem", "m"=>"math", "b"=>"bio", "z"=>"zoo", "ma"=>"mat", "e"=>"eng", "lr"=>"lr", "ss"=>"soc", "cs"=>"cs", "gk"=>"gk", "sc"=>"sci"  );
    foreach ($sub_data as $key => $value) 
    {
      if($paper_data['n'.$value] != '' && $paper_data['n'.$value] != '0')
      {
        $test_subjects[$key] .= $value;
      }
      else
      {
        continue;
      }
    
    }

    return $test_subjects;
  }

  $subjects = get_subject($paper_data);
  
  $spr = array();
  
  $total_obt = NULL;
  $total_corr = NULL;
  $total_wrong = NULL;
  $total_unattempt = NULL;
  foreach ($subjects as $key => $value) 
  {
    $sub_data = array( "p"=>"Physics", "c"=>"Chemistry", "m"=>"Maths", "b"=>"Botany", "z"=>"Zoology", "ma"=>"Mentalability", "e"=>"English", "lr"=>"Logical Reasoning", "ss"=>"Social Science", "cs"=>"Computer Science", "gk"=>"General Knowledge", "sc"=>"Science"  );
    $curr_sub = $value;
    $spr[$curr_sub] = array();
    $spr[$curr_sub]['ques'] = $paper_data['n'.$value];
    $spr[$curr_sub]['marks'] = $paper_data['ntot'.$value];
    $spr[$curr_sub]['obt'] = round(($spr_data[$key.'obt']),1);
    $spr[$curr_sub]['corr'] = $spr_data[$key.'corr'];
    $spr[$curr_sub]['wrong'] = $spr_data[$key.'wrong'];
    $spr[$curr_sub]['negmarks'] = round(($spr_data[$key.'negmarks']),1);
    $spr[$curr_sub]['unattempt']=$paper_data['n'.$value]-($spr_data[$key.'corr']+$spr_data[$key.'wrong']);//
    $spr[$curr_sub]['corr_accuracy']=round((($spr_data[$key.'corr']*100)/($spr_data[$key.'corr']+$spr_data[$key.'wrong'])),1);//
    $spr[$curr_sub]['corr_percent']=round((($spr_data[$key.'corr']*100)/($spr_data[$key.'corr']+$spr_data[$key.'wrong'])),1);//
    $spr[$curr_sub]['wrong_percent']=round((($spr_data[$key.'wrong']*100)/$paper_data['n'.$value]),1);//
    $spr[$curr_sub]['unattempt_percent']=round((($spr[$curr_sub]['unattempt']*100)/$paper_data['n'.$value]),1);//
    $spr[$curr_sub]['marks_correct']=$spr_data[$key.'obt']+(-1*$spr_data[$key.'negmarks']);//
    $spr[$curr_sub]['max_obt'] = $max_spr[$key.'obt'];  //
    $spr[$curr_sub]['rank']  = $spr_data[$key.'rank'];  //

    if (array_key_exists($key, $sub_data)) 
    {
        $spr[$curr_sub]['subject']=$sub_data[$key];//
    }

    $total_obt += $spr[$curr_sub]['obt'];
    $total_corr += $spr[$curr_sub]['corr'];
    $total_wrong += $spr[$curr_sub]['wrong'];
    $total_negative += $spr[$curr_sub]['negmarks'];
    $total_unattempt += $spr[$curr_sub]['unattempt'];
    $total_max_obt += $spr[$curr_sub]['max_obt'];
    $total_quesn += $paper_data['n'.$value];
    $total_marksn += $paper_data['ntot'.$value];
    //break;
  }

  foreach ($subjects as $key => $value) 
  {
    $curr_sub = 'total';
    $spr[$curr_sub] = array();
    $spr[$curr_sub]['ques']  = $total_quesn;  //
    $spr[$curr_sub]['marks']  = $total_marksn;  //
    $spr[$curr_sub]['obt'] = $total_obt;  //
    $spr[$curr_sub]['corr'] = $total_corr;  //
    $spr[$curr_sub]['wrong'] = $total_wrong;  //
    $spr[$curr_sub]['negmarks'] = $total_negative;//
    $spr[$curr_sub]['unattempt'] = $total_unattempt;  //
    
    $spr[$curr_sub]['corr_accuracy']=round((($spr[$curr_sub]['corr']*100)/($spr[$curr_sub]['corr']+$spr[$curr_sub]['wrong'])),1);  //
    $spr[$curr_sub]['corr_percent'] = round((($spr[$curr_sub]['corr']*100)/($spr[$curr_sub]['corr']+$spr[$curr_sub]['wrong'])),1);  //
    $spr[$curr_sub]['wrong_percent'] = round((($spr[$curr_sub]['wrong']*100)/$total_quesn),1);  //
    $spr[$curr_sub]['unattempt_percent'] = round((($spr[$curr_sub]['unattempt']*100)/$total_quesn),1);  //
    $spr[$curr_sub]['marks_correct']  = (($spr[$curr_sub]['obt'])+ (-1*($spr[$curr_sub]['negmarks'])));  //
    $spr[$curr_sub]['max_obt']  = $total_max_obt;  //
    $spr[$curr_sub]['rank']  = $spr_data['rank'];  //
    $spr[$curr_sub]['subject']='Total';//
    //break;
  }
  
  return($spr);
}


/*
    function for closing database connection
    arguments - $link_id(from connection function)
    return - NULL 
    summary - closes the database connection
*/
function close_analysis_db($link_id){
    $link_id->close();
}


// main code starts here

$link_id=connect_analysis_db(); 
$adm_link=connect_admission_db();  			     // connection fn
$user=get_user_info($adm_link);   			       // user_info
$paper_data=paper_info();   				         //Test Paper Info
$max_spr=max_spr();                          //SPR of MAX_MARKS data
$spr_info=user_test_report($paper_data,$max_spr);     //Student Test Report
close_analysis_db($link_id);                 // close database connection

$userid=$_GET['userid'];
$pid=$_GET['paperid'];
$hashdata=$_GET['hashdata'];
$error=NULL;
$data=NULL;

if($hashdata==md5($userid.'--'.$pid)){

  $data=array('users'=>$user,'papers'=>$paper_data,'spr'=>$spr_info,'max_spr'=>$max_spr);
  $data = @json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  echo $data;
}
else{
  echo $error=1;
}
?>