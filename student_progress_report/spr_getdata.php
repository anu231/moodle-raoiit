<?php

error_reporting(0);
$username=NULL;
$id=NULL;
$userid=NULL;
$paperid=NULL;
$hash=NULL;


include('db_connect.php');

/*
    function for get all the active_user_info form batch
    parameters - 
    return - $user (userinfo of active batches)  
    summary - takes active batch info & returns all active student of that batch.
*/
function get_user_info($link_id){
  $userid=$_GET['userid'];
  $batch=NULL;
  
    $user = array();
    $sql="SELECT u.*,c.name as centrename FROM userinfo as u JOIN centreinfo as c ON u.centre = c.id WHERE userid='$userid'";
    $result=mysqli_query($link_id,$sql);
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
    //echo "<pre>";
    //print_r($user);
    
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
  $subject_ques=array();
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
  //echo "<pre>";
  
  return($paper_data);

  //echo "<pre>";
  //print_r($spr_data);
   
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
  //echo "<pre>";
  //echo "<pre>";
  //print_r($res_max_spr);
  return($res_max_spr);

  
   
}


/*
    function for get test report of AVG_MARKS student with perticular paperid(pid) in cURL
    parameters - 
    return - $avg_spr (AVG_MARKS student test performance data)  
    summary - Returns AVG_MARKS student test performance data of that paperid(pid)
*/

function avg_spr(){
  include('config.php');
  $avg_spr = array();
  $pid=$_GET['paperid'];
  $avg_spr = $spr_cURL."username=999990&pid=$pid";    // cURL to get the avg_spr
  //$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

  $ch1 = curl_init();
  curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch1, CURLOPT_URL,$avg_spr);
  //curl_setopt($ch, CURLOPT_USERAGENT, $agent);
  $response1=curl_exec($ch1);
  curl_close($ch1);
  //var_dump(json_decode($result,true));
  $res_avg_spr = json_decode($response1, true);
  //echo "<pre>";
  //echo "<pre>";
  //print_r($res_avg_spr);
  return($res_avg_spr);

  
   
}

/*
    function for get test report of student with perticular paperid(pid) in cURL
    parameters - $paper_data(paper info of perticular paper id)
    return - $spr_data (student test performance data)  
    summary - Takes paper_data(paper info) & returns student test performance data of that paper
*/

function user_test_report($paper_data,$max_spr,$avg_spr){
  include('config.php');
  $spr_data = array();
  $userid=$_GET['userid'];
  $pid=$_GET['paperid'];
  $hashdata=$_GET['hashdata'];
  
  $loginUrl = $spr_cURL."username=$userid&pid=$pid";   // cURL to get student performance info for perticular paper_id
  //$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_URL,$loginUrl);
  //curl_setopt($ch, CURLOPT_USERAGENT, $agent);
  $response=curl_exec($ch);
  curl_close($ch);
  //var_dump(json_decode($result,true));
  $spr_data = json_decode($response, true);
  //echo "<pre>";
  //print_r($spr_data);

  $spr=array();
  $spr+=$spr_data;

  $subject = array( "p", "c", "m", "b", "z", "ma", "e", "lr", "ss", "cs", "gk", "sc" );
  $subjectdata = array( "corr", "wrong", "obt", "negmarks");
  for($i=0;$i<count($subjectdata);$i++)
  {
    for($j=0;$j<count($subject);$j++)
    {
      
      $spr['total'.$subjectdata[$i]]+=$spr_data[0][$subject[$j].$subjectdata[$i]];
      $spr[$subject[$j].$subjectdata[$i]]+=$spr_data[0][$subject[$j].$subjectdata[$i]];
      $spr['max_spr_'.$subject[$j].$subjectdata[$i]]+=$max_spr[0][$subject[$j].$subjectdata[$i]];
      $spr['avg_spr_'.$subject[$j].$subjectdata[$i]]+=$avg_spr[0][$subject[$j].$subjectdata[$i]];
    }

  }

  $subject = array( "n", "ntot");
  $subjectdata = array( "phy", "chem", "math", "bio", "zoo", "mat", "eng", "lr", "soc", "cs", "gk", "sci"  );
  for($i=0;$i<count($subjectdata);$i++)
  {
    for($j=0;$j<count($subject);$j++)
    {
      $spr['total_ques'.$subject[$j]]+=$paper_data[$subject[$j].$subjectdata[$i]];
      $spr[$subject[$j].$subjectdata[$i]]+=$paper_data[$subject[$j].$subjectdata[$i]];

    }
  }
  
  $subject = array( "p", "c", "m", "b" ,"z", "ma", "e", "lr", "ss", "cs", "gk", "sc");
  $subjectdata = array( "phy", "chem", "math", "bio", "zoo", "mat", "eng", "lr", "soc", "cs", "gk", "sci"  );
  $calculatdata = array( "unattempt", "corr_accuracy", "corr_percent", "wrong_percent","unattempt_percent","marks_correct");
    
  for($i=0;$i<count($calculatdata);$i++)
  {
    for($j=0;$j<count($subject);$j++)
    {

      $spr[$subject[$j].$calculatdata[$i]]+=$spr['n'.$subjectdata[$j]]-($spr_data[0][$subject[$j].'corr']+$spr_data[0][$subject[$j].'wrong']);

      if($spr_data[0][$subject[$j].'obt']==0 && $spr_data[0][$subject[$j].'corr']==0 && $spr_data[0][$subject[$j].'wrong']==0 && $spr_data[0][$subject[$j].'negmarks']==0){
        $spr[$subject[$j].$calculatdata[$i+1]]+="0";
        $spr[$subject[$j].$calculatdata[$i+2]]+="0";
        $spr[$subject[$j].$calculatdata[$i+3]]+="0";
      }
      else{
        $spr[$subject[$j].$calculatdata[$i+1]]+=round((($spr_data[0][$subject[$j].'corr']*100)/($spr_data[0][$subject[$j].'corr']+$spr_data[0][$subject[$j].'wrong'])),1);
        $spr[$subject[$j].$calculatdata[$i+2]]+=round((($spr_data[0][$subject[$j].'corr']*100)/$spr['n'.$subjectdata[$j]]),1);
        $spr[$subject[$j].$calculatdata[$i+3]]+=round((($spr_data[0][$subject[$j].'wrong']*100)/$spr['n'.$subjectdata[$j]]),1);
      }
      $spr[$subject[$j].$calculatdata[$i+4]]+=round(100-($spr[$subject[$j].$calculatdata[$i+2]]+$spr[$subject[$j].$calculatdata[$i+3]]),1);
      $spr[$subject[$j].$calculatdata[$i+5]]+=$spr_data[0][$subject[$j].'obt']+(-1*$spr_data[0][$subject[$j].'negmarks']);

    }
    break;
  }

  $spr['total'.$calculatdata[0]]=$spr['total_quesn']-($spr['totalcorr']+$spr['totalwrong']);
      $spr['total'.$calculatdata[1]]=round((($spr['totalcorr']*100)/($spr['totalcorr']+$spr['totalwrong'])),1);
  $spr['total'.$calculatdata[2]] = round((($spr['totalcorr']*100)/$spr['total_quesn']),1);
  $spr['total'.$calculatdata[3]] = round((($spr['totalwrong']*100)/$spr['total_quesn']),1);
  $spr['total'.$calculatdata[4]] = round((($spr['totalunattempt']*100)/$spr['total_quesn']),1);
  $spr['total'.$calculatdata[5]] = $spr_data[0]['marksobt'] + (-1*($spr_data[0]['negmarks']));

  $spr['totalmarks'] = $spr['total_quesntot'];
  $spr['negmarks'] = round($spr_data[0]['negmarks'],0);
  $spr['marksobt'] = round($spr_data[0]['marksobt'],0);
  $spr['rank'] = $spr_data[0]['rank'];


  $count=NULL;
  $subject = array( "n");
  $subject1 = array( "p", "c", "m", "b", "z", "ma", "e", "lr", "ss", "cs", "gk", "sc" );
  $subjectdata = array( "phy", "chem", "math", "bio", "zoo", "mat", "eng", "lr", "soc", "cs", "gk", "sci"  );
  $printSubject = array( "Physics", "Chemistry", "Mathematics", "Biology","Zoology","Mental Ability","English","Logical Reasoning","Social Science","Computer Science","General Knowledge","Science");



  //echo "<pre>";
  //print_r($spr);  
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



$link_id=connect_analysis_db();              // connection fn
$user=get_user_info($link_id);               // user_info
$paper_data=paper_info();                    //Test Paper Info
$max_spr=max_spr();                          //SPR of MAX_MARKS data
$avg_spr=avg_spr();                          //SPR of AVG_MARKS data
$spr_data=user_test_report($paper_data,$max_spr,$avg_spr);     //Student Test Report
close_analysis_db($link_id);                 // close database connection

//header("Location: util_spr.html?user_info=". json_encode($user)."&spr_data=".json_encode($spr_data));

//echo "<pre>";

$userid=$_GET['userid'];
$pid=$_GET['paperid'];
$hashdata=$_GET['hashdata'];
$error=NULL;
$data=NULL;

if($hashdata==md5($userid.'--'.$pid)){

  $data=array('users'=>$user,'papers'=>$paper_data,'spr'=>$spr_data,'max_spr'=>$max_spr,'avg_spr'=>$avg_spr);
  $data = @json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  echo $data;
}
else{
  echo $error=1;
}
?>