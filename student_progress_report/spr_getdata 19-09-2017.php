<?php


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
    function for get test report of student with perticular paperid(pid) in cURL
    parameters - $paper_data(paper info of perticular paper id)
    return - $spr_data (student test performance data)  
    summary - Takes paper_data(paper info) & returns student test performance data of that paper
*/

function user_test_report($paper_data){
  include('config.php');
  $spr_data = array();
  $userid=$_GET['userid'];
  $pid=$_GET['paperid'];
  $hashdata=$_GET['hashdata'];
  
  
    //for total questions
    $total_qs=NULL;
    $total_corr=NULL;
    $total_wrong=NULL;
    $total_unattempt=NULL;
    $total_corr_percent=NULL;
    $total_wrong_percent=NULL;
    $total_unattempt_percent=NULL;
    $total_corr_accuracy=NULL;
    $total_marks=NULL;
    $total_marks_obtained=NULL;
    $negative_marks=NULL;
    $total_marks_correct=NULL;
    $total_marks_wrong=NULL;

    //For Physics questions
    $phy_ques=NULL;
    $total_unattempt=NULL;
    $phy_corr_percent=NULL;
    $phy_wrong_percent=NULL;
    $phy_unattempt_percent=NULL;
    $phy_corr_accuracy=NULL;
    $phy_total_marks=NULL;
    $phy_marks_obtained=NULL;
    $phy_marks_correct=NULL;
    $phy_marks_wrong=NULL;

    //For Chemistry questions
    $chem_ques=NULL;
    $chem_unattempt=NULL;
    $chem_corr_percent=NULL;
    $chem_wrong_percent=NULL;
    $chem_unattempt_percent=NULL;
    $chem_corr_accuracy=NULL;
    $chem_total_marks=NULL;
    $chem_marks_obtained=NULL;
    $chem_marks_correct=NULL;
    $chem_marks_wrong=NULL;


    //For Maths questions
    $maths_ques=NULL;
    $maths_unattempt=NULL;
    $maths_corr_percent=NULL;
    $maths_wrong_percent=NULL;
    $maths_unattempt_percent=NULL;
    $maths_corr_accuracy=NULL;
    $maths_total_marks=NULL;
    $maths_marks_obtained=NULL;
    $maths_marks_correct=NULL;
    $maths_marks_wrong=NULL;

    //For Biology questions
    $bio_ques=NULL;
    $bio_unattempt=NULL;
    $bio_corr_percent=NULL;
    $bio_wrong_percent=NULL;
    $bio_unattempt_percent=NULL;
    $bio_corr_accuracy=NULL;
    $bio_total_marks=NULL;
    $bio_marks_obtained=NULL;
    $bio_marks_correct=NULL;
    $bio_marks_wrong=NULL;
  
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
  
  $total_qs=$paper_data['numques'];
  $total_marks=$paper_data['maximummarks'];
  $phy_ques=$paper_data['nphy'];
  $chem_ques=$paper_data['nchem'];
  $maths_ques=$paper_data['nmath'];
  $bio_ques=$paper_data['nbio'];

  $paper_type=strtolower($paper_data['name']);

  if($paper_data['stream'] == 'Eng'){
    $phy_total_marks = $total_marks/3;
    $chem_total_marks = $total_marks/3;
    $maths_total_marks = $total_marks/3;
    $bio_total_marks = 0;
  }

  if($paper_data['stream']== 'Med' && strpos($paper_type, 'neet') !== false){
    $phy_total_marks = $paper_data['maximummarks']/4;
    $chem_total_marks = $paper_data['maximummarks']/4;
    $maths_total_marks = 0;
    $bio_total_marks = $paper_data['maximummarks']/2;
  }

  if($paper_data['stream']== 'Med' && strpos($paper_type, 'aiims') !== false){
    $phy_total_marks = ($total_marks-20)/3;
    $chem_total_marks = ($total_marks-20)/3;
    $maths_total_marks = 0;
    $bio_total_marks = (($total_marks-20)/3)+20;
  } 

  $elementCount  = count($spr_data);
  if($spr_data[0]['paperid']==$pid){
    $subject = array( "p", "c", "m", "b" );
    $subjectdata = array( "corr", "wrong", "obt", "negmarks" );
    $paper_subjects = array( "phy", "chem", "maths", "bio" );
    $calculatedData=NULL;
    $dataResult=[];

    for($i=0;$i<count($subjectdata);$i++)
    {
      for($j=0;$j<count($subject);$j++)
      {
        $calculatedData+=$spr_data[0][$subject[$j].$subjectdata[$i]];

      }
      $p=$paper_subjects[$i].'_ques';
      $unattempt+=$$p-($spr_data[0][$subject[$i].'corr']+$spr_data[0][$subject[$i].'wrong']);
      if($spr_data[0][$subject[$i].'obt']==0 && $spr_data[0][$subject[$i].'corr']==0 && $spr_data[0][$subject[$i].'wrong']==0 && $spr_data[0][$subject[$i].'negmarks']==0){
            $corrAccuracy='0';
            $corrPercent='0';
            $wrongPercent='0';
          }
          else{
            $corrAccuracy+=round((($spr_data[0][$subject[$i].'corr']*100)/($spr_data[0][$subject[$i].'corr']+$spr_data[0][$subject[$i].'wrong'])),1);;
            $corrPercent+=round((($spr_data[0][$subject[$i].'corr']*100)/$$p),1);
            $wrongPercent+=round((($spr_data[0][$subject[$i].'wrong']*100)/$$p),1);
            
          }
      $unattemptPercent+=round(100-($corrPercent+$wrongPercent),1);
      $marksCorrect+=$spr_data[0][$subject[$i].'obt']+(-1*$spr_data[0][$subject[$i].'negmarks']);
      //$marksWrong+= -($spr_data[0][$subject[$i].'wrong']*$neg_marks_per_wrong);
      
      array_push($dataResult,$calculatedData,$unattempt,$corrAccuracy,$corrPercent,$wrongPercent,$unattemptPercent,$marksCorrect);
        
        $calculatedData=0;
        $unattempt=0;
        $corrAccuracy=0;
        $corrPercent=0;
        $wrongPercent=0;
        $unattemptPercent=0;
        $marksCorrect=0;
    }

    $total_corr = $dataResult[0];
    $total_wrong = $dataResult[7];
    $total_marks_obtained = $dataResult[14];
    $negative_marks = $dataResult[21];

    $total_unattempt = $total_qs-($total_corr+$total_wrong);
    $total_corr_percent = round((($total_corr*100)/$total_qs),1);
    $total_wrong_percent = round((($total_wrong*100)/$total_qs),1);
    $total_unattempt_percent = round((($total_unattempt*100)/$total_qs),1);
    $total_corr_accuracy = round((($total_corr*100)/($total_corr+$total_wrong)),1);
    $total_marks = $paper_data['maximummarks'];
    $total_marks_correct = $spr_data[0]['marksobt'] + (-1*($spr_data[0]['negmarks']));
    $total_marks_wrong = -($negative_marks);

    /* For Physics Marks Calculation */
    $phy_unattempt = $dataResult[1];
    $phy_corr_accuracy = $dataResult[2];
    $phy_corr_percent = $dataResult[3];
    $phy_wrong_percent = $dataResult[4];
    $phy_unattempt_percent = $dataResult[5];
    $phy_marks_correct = $dataResult[6];
   
    /* For Chemistry Marks Calculation */
    $chem_unattempt = $dataResult[8];
    $chem_corr_accuracy = $dataResult[9];
    $chem_corr_percent = $dataResult[10];
    $chem_wrong_percent = $dataResult[11];
    $chem_unattempt_percent = $dataResult[12];
    $chem_marks_correct = $dataResult[13];
    
    /* For Mathematics Marks Calculation */
    $maths_unattempt = $dataResult[15];
    $maths_corr_accuracy = $dataResult[16];
    $maths_corr_percent = $dataResult[17];
    $maths_wrong_percent = $dataResult[18];
    $maths_unattempt_percent = $dataResult[19];
    $maths_marks_correct = $dataResult[20];
    
    /* For Bio Marks Calculation */
    $bio_unattempt = $dataResult[22];
    $bio_corr_accuracy = $dataResult[23];
    $bio_corr_percent = $dataResult[24];
    $bio_wrong_percent = $dataResult[25];
    $bio_unattempt_percent = $dataResult[26];
    $bio_marks_correct = $dataResult[27];
    
    $spr_data[0]['total_qs'] = $total_qs;
    $spr_data[0]['total_marks'] = $total_marks;

    // For total paper
    $spr_data[0]['total_corr'] = $total_corr;
    $spr_data[0]['total_wrong'] = $total_wrong;
    $spr_data[0]['total_unattempt'] = $total_unattempt;
    $spr_data[0]['total_corr_percent'] = $total_corr_percent;
    $spr_data[0]['total_wrong_percent'] = $total_wrong_percent;
    $spr_data[0]['total_unattempt_percent'] = $total_unattempt_percent;
    $spr_data[0]['total_corr_accuracy'] = $total_corr_accuracy;
    $spr_data[0]['total_marks_obtained'] = $total_marks_obtained;
    $spr_data[0]['negative_marks'] = $negative_marks;
    $spr_data[0]['total_marks_correct'] = $total_marks_correct;
    $spr_data[0]['total_marks_wrong'] = $total_marks_wrong;

    // For Physics
    $spr_data[0]['phy_ques'] = $phy_ques;
    $spr_data[0]['phy_unattempt'] = $phy_unattempt;
    $spr_data[0]['phy_corr_percent'] = $phy_corr_percent;
    $spr_data[0]['phy_wrong_percent'] = $phy_wrong_percent;
    $spr_data[0]['phy_unattempt_percent'] = $phy_unattempt_percent;
    $spr_data[0]['phy_corr_accuracy'] = $phy_corr_accuracy;
    $spr_data[0]['phy_total_marks'] = $phy_total_marks;
    $spr_data[0]['phy_marks_correct'] = $phy_marks_correct;
    
    // For Chemistry
    $spr_data[0]['chem_ques'] = $chem_ques;
    $spr_data[0]['chem_unattempt'] = $chem_unattempt;
    $spr_data[0]['chem_corr_percent'] = $chem_corr_percent;
    $spr_data[0]['chem_wrong_percent'] = $chem_wrong_percent;
    $spr_data[0]['chem_unattempt_percent'] = $chem_unattempt_percent;
    $spr_data[0]['chem_corr_accuracy'] = $chem_corr_accuracy;
    $spr_data[0]['chem_total_marks'] = $chem_total_marks;
    $spr_data[0]['chem_marks_correct'] = $chem_marks_correct;
    
    if($paper_data['stream'] == 'Eng'){
      // For Maths
      $spr_data[0]['maths_ques'] = $maths_ques;
      $spr_data[0]['maths_unattempt'] = $maths_unattempt;
      $spr_data[0]['maths_corr_percent'] = $maths_corr_percent;
      $spr_data[0]['maths_wrong_percent'] = $maths_wrong_percent;
      $spr_data[0]['maths_unattempt_percent'] = $maths_unattempt_percent;
      $spr_data[0]['maths_corr_accuracy'] = $maths_corr_accuracy;
      $spr_data[0]['maths_total_marks'] = $maths_total_marks;
      $spr_data[0]['maths_marks_correct'] = $maths_marks_correct;
    }
  
  if($paper_data['stream'] == 'Med'){
  // For Biology
      $spr_data[0]['bio_ques'] = $bio_ques;
      $spr_data[0]['bio_unattempt'] = $bio_unattempt;
      $spr_data[0]['bio_corr_percent'] = $bio_corr_percent;
      $spr_data[0]['bio_wrong_percent'] = $bio_wrong_percent;
      $spr_data[0]['bio_unattempt_percent'] = $bio_unattempt_percent;
      $spr_data[0]['bio_corr_accuracy'] = $bio_corr_accuracy;
      $spr_data[0]['bio_total_marks'] = $bio_total_marks;
      $spr_data[0]['bio_marks_correct'] = $bio_marks_correct;
    }

  } 
  //echo "<pre>";
  //print_r($spr_data);  
  return($spr_data);

  
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
$spr_data=user_test_report($paper_data);     //Student Test Report
close_analysis_db($link_id);                 // close database connection

//header("Location: util_spr.html?user_info=". json_encode($user)."&spr_data=".json_encode($spr_data));

//echo "<pre>";

$userid=$_GET['userid'];
$pid=$_GET['paperid'];
$hashdata=$_GET['hashdata'];
$error=NULL;

if($hashdata==md5($userid.'--'.$pid)){
  echo json_encode(array('users'=>$user,'papers'=>$paper_data,'spr'=>$spr_data,'max_spr'=>$max_spr,'avg_spr'=>$avg_spr));
}
else{
  echo $error=1;
}
?>