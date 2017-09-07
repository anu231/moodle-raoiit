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

  $subjectwise_ques = array();

  $subjectwise_ques=get_subjectwise_ques($paper_data);

  $paper_data['phy_ques'] = $subjectwise_ques['phy_ques'];
  $paper_data['chem_ques'] = $subjectwise_ques['chem_ques'];
  $paper_data['maths_ques'] = $subjectwise_ques['maths_ques'];
  $paper_data['bio_ques'] = $subjectwise_ques['bio_ques'];

  $paper_data['phy_total_marks'] = $subjectwise_ques['phy_total_marks'];
  $paper_data['chem_total_marks'] = $subjectwise_ques['chem_total_marks'];
  $paper_data['maths_total_marks'] = $subjectwise_ques['maths_total_marks'];
  $paper_data['bio_total_marks'] = $subjectwise_ques['bio_total_marks'];


	return($paper_data);

	//echo "<pre>";
    //print_r($spr_data);
   
}


/*
    function for put subjectwise question info(count) of the paper
    parameters - $paper_data(paper info of perticular paper id)
    return - $subjectwise_ques (subjectwise question info(count))  
    summary - Takes paper_data(paper info) & returns subjectwise question info(count) of that paper
*/

function get_subjectwise_ques($paper_data){

  $subjectwise_ques = array();
  if($paper_data['stream']== 'Eng'){
    $subjectwise_ques += array("phy_ques" => 30);
    $subjectwise_ques += array("chem_ques" => 30);
    $subjectwise_ques += array("maths_ques" => 30);
    $subjectwise_ques += array("phy_total_marks" => 120);
    $subjectwise_ques += array("chem_total_marks" => 120);
    $subjectwise_ques += array("maths_total_marks" => 120);
  }

  if($paper_data['stream']== 'Med'){
    $subjectwise_ques += array("phy_ques" => 45);
    $subjectwise_ques += array("chem_ques" => 45);
    $subjectwise_ques += array("bio_ques" => 90);
    $subjectwise_ques += array("phy_total_marks" => 180);
    $subjectwise_ques += array("chem_total_marks" => 180);
    $subjectwise_ques += array("bio_total_marks" => 360);
  }

  

  
  //echo "<pre>";
  //print_r($subjectwise_ques);
  return($subjectwise_ques);
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
  
  $i=NULL;

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
    $marks_per_right_qs=NULL;
    $neg_marks_per_wrong=NULL;
    $total_marks_correct=NULL;
    $total_marks_wrong=NULL;

    //For Physocs questions
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
  $phy_ques=$paper_data['phy_ques'];
  $chem_ques=$paper_data['chem_ques'];
  $maths_ques=$paper_data['maths_ques'];
  $bio_ques=$paper_data['bio_ques'];

  $phy_total_marks=$paper_data['phy_total_marks'];
  $chem_total_marks=$paper_data['chem_total_marks'];
  $maths_total_marks=$paper_data['maths_total_marks'];
  $bio_total_marks=$paper_data['bio_total_marks'];

  $marks_per_right_qs=$paper_data['sccor'];
  $neg_marks_per_wrong=$paper_data['scnegmarks'];


	$elementCount  = count($spr_data);

    	
    for($i=0;$i<$elementCount;$i++){
      	if($spr_data[$i]['paperid']==$pid){

      		$spr_data[$i]['total_qs'] = $total_qs;
      		$spr_data[$i]['total_marks'] = $total_marks;

      		//For Physics questions
      		$total_corr = $spr_data[$i]['pcorr']+$spr_data[$i]['ccorr']+$spr_data[$i]['mcorr']+$spr_data[$i]['bcorr']+$spr_data[$i]['zcorr']+$spr_data[$i]['macorr']+$spr_data[$i]['ecorr']+$spr_data[$i]['lrcorr']+$spr_data[$i]['sscorr']+$spr_data[$i]['cscorr']+$spr_data[$i]['gkcorr']+$spr_data[$i]['sccorr'];
      		$total_wrong = $spr_data[$i]['pwrong']+$spr_data[$i]['cwrong']+$spr_data[$i]['mwrong']+$spr_data[$i]['bwrong']+$spr_data[$i]['zwrong']+$spr_data[$i]['mawrong']+$spr_data[$i]['ewrong']+$spr_data[$i]['lrwrong']+$spr_data[$i]['sswrong']+$spr_data[$i]['cswrong']+$spr_data[$i]['gkwrong']+$spr_data[$i]['scwrong'];
      		$total_unattempt = ($total_qs-($total_corr+$total_wrong));

      		$total_corr_percent=round((($total_corr*100)/$total_qs),1);
	        $total_wrong_percent=round((($total_wrong*100)/$total_qs),1);
	        $total_unattempt_percent=round((($total_unattempt*100)/$total_qs),1);
	        $total_corr_accuracy=round((($total_corr*100)/($total_corr+$total_wrong)),1);
	        $total_marks=$paper_data['maximummarks'];

	        $total_marks_obtained=$spr_data[$i]['pobt']+$spr_data[$i]['cobt']+$spr_data[$i]['mobt']+$spr_data[$i]['bobt']+$spr_data[$i]['zobt']+$spr_data[$i]['maobt']+$spr_data[$i]['eobt']+$spr_data[$i]['lrobt']+$spr_data[$i]['ssobt']+$spr_data[$i]['csobt']+$spr_data[$i]['gkobt']+$spr_data[$i]['scobt'];
	        $negative_marks=$spr_data[$i]['pnegmarks']+$spr_data[$i]['cnegmarks']+$spr_data[$i]['mnegmarks']+$spr_data[$i]['bnegmarks']+$spr_data[$i]['znegmarks']+$spr_data[$i]['manegmarks']+$spr_data[$i]['enegmarks']+$spr_data[$i]['lrnegmarks']+$spr_data[$i]['ssnegmarks']+$spr_data[$i]['csnegmarks']+$spr_data[$i]['gknegmarks']+$spr_data[$i]['scnegmarks']; 

          $total_marks_correct=($spr_data[$i]['pcorr']+$spr_data[$i]['ccorr']+$spr_data[$i]['mcorr']+$spr_data[$i]['bcorr']+$spr_data[$i]['zcorr']+$spr_data[$i]['macorr']+$spr_data[$i]['ecorr']+$spr_data[$i]['lrcorr']+$spr_data[$i]['sscorr']+$spr_data[$i]['cscorr']+$spr_data[$i]['gkcorr']+$spr_data[$i]['sccorr'])*$marks_per_right_qs;

          $total_marks_wrong = -(($spr_data[$i]['pwrong']+$spr_data[$i]['cwrong']+$spr_data[$i]['mwrong']+$spr_data[$i]['bwrong']+$spr_data[$i]['zwrong']+$spr_data[$i]['mawrong']+$spr_data[$i]['ewrong']+$spr_data[$i]['lrwrong']+$spr_data[$i]['sswrong']+$spr_data[$i]['cswrong']+$spr_data[$i]['gkwrong']+$spr_data[$i]['scwrong'])*$neg_marks_per_wrong);

	      //For Physics questions
        $phy_unattempt=$phy_ques-($spr_data[$i]['pcorr']+$spr_data[$i]['pwrong']);
        $phy_corr_percent=round((($spr_data[$i]['pcorr']*100)/$phy_ques),1);
        $phy_wrong_percent=round((($spr_data[$i]['pwrong']*100)/$phy_ques),1);
        $phy_unattempt_percent=round((($phy_unattempt*100)/$phy_ques),1);
        $phy_corr_accuracy=round((($spr_data[$i]['pcorr']*100)/($spr_data[$i]['pcorr']+$spr_data[$i]['pwrong'])),1);
        $phy_marks_correct=$spr_data[$i]['pcorr']*$marks_per_right_qs;
        $phy_marks_wrong=-($spr_data[$i]['pwrong']*$neg_marks_per_wrong);

        //For Chemistry questions
        $chem_unattempt=$chem_ques-($spr_data[$i]['ccorr']+$spr_data[$i]['cwrong']);
        $chem_corr_percent=round((($spr_data[$i]['ccorr']*100)/$chem_ques),1);
        $chem_wrong_percent=round((($spr_data[$i]['cwrong']*100)/$chem_ques),1);
        $chem_unattempt_percent=round((($chem_unattempt*100)/$chem_ques),1);
        $chem_corr_accuracy=round((($spr_data[$i]['ccorr']*100)/($spr_data[$i]['ccorr']+$spr_data[$i]['cwrong'])),1);
        $chem_marks_correct=$spr_data[$i]['ccorr']*$marks_per_right_qs;
        $chem_marks_wrong=-($spr_data[$i]['cwrong']*$neg_marks_per_wrong);

        //For Maths questions
        $maths_unattempt=$maths_ques-($spr_data[$i]['mcorr']+$spr_data[$i]['mwrong']);
        $maths_corr_percent=round((($spr_data[$i]['mcorr']*100)/$maths_ques),1);
        $maths_wrong_percent=round((($spr_data[$i]['mwrong']*100)/$maths_ques),1);
        $maths_unattempt_percent=round((($maths_unattempt*100)/$maths_ques),1);
        $maths_corr_accuracy=round((($spr_data[$i]['mcorr']*100)/($spr_data[$i]['mcorr']+$spr_data[$i]['mwrong'])),1);
        $maths_marks_correct=$spr_data[$i]['mcorr']*$marks_per_right_qs;
        $maths_marks_wrong=-($spr_data[$i]['mwrong']*$neg_marks_per_wrong);

        
        //For Biology questions
        $bio_unattempt=$bio_ques-($spr_data[$i]['bcorr']+$spr_data[$i]['bwrong']);
        $bio_corr_percent=round((($spr_data[$i]['bcorr']*100)/$bio_ques),1);
        $bio_wrong_percent=round((($spr_data[$i]['bwrong']*100)/$bio_ques),1);
        $bio_unattempt_percent=round((($bio_unattempt*100)/$bio_ques),1);
        $bio_corr_accuracy=round((($spr_data[$i]['bcorr']*100)/($spr_data[$i]['bcorr']+$spr_data[$i]['bwrong'])),1);
        $bio_marks_correct=$spr_data[$i]['bcorr']*$marks_per_right_qs;
        $bio_marks_wrong=-($spr_data[$i]['bwrong']*$neg_marks_per_wrong);
        

        	// For total paper
      		$spr_data[$i]['total_corr'] = $total_corr;
      		$spr_data[$i]['total_wrong'] = $total_wrong;
      		$spr_data[$i]['total_unattempt'] = $total_unattempt;
      		$spr_data[$i]['total_corr_percent'] = $total_corr_percent;
      		$spr_data[$i]['total_wrong_percent'] = $total_wrong_percent;
      		$spr_data[$i]['total_unattempt_percent'] = $total_unattempt_percent;
      		$spr_data[$i]['total_corr_accuracy'] = $total_corr_accuracy;
      		$spr_data[$i]['total_marks_obtained'] = $total_marks_obtained;
      		$spr_data[$i]['negative_marks'] = $negative_marks;
          $spr_data[$i]['total_marks_correct'] = $total_marks_correct;
          $spr_data[$i]['total_marks_wrong'] = $total_marks_wrong;

      		// For Physics
      		$spr_data[$i]['phy_ques'] = $phy_ques;
      		$spr_data[$i]['phy_unattempt'] = $phy_unattempt;
      		$spr_data[$i]['phy_corr_percent'] = $phy_corr_percent;
      		$spr_data[$i]['phy_wrong_percent'] = $phy_wrong_percent;
      		$spr_data[$i]['phy_unattempt_percent'] = $phy_unattempt_percent;
      		$spr_data[$i]['phy_corr_accuracy'] = $phy_corr_accuracy;
      		$spr_data[$i]['phy_total_marks'] = $phy_total_marks;
          $spr_data[$i]['phy_marks_correct'] = $phy_marks_correct;
          $spr_data[$i]['phy_marks_wrong'] = $phy_marks_wrong;

      		// For Chemistry
      		$spr_data[$i]['chem_ques'] = $chem_ques;
      		$spr_data[$i]['chem_unattempt'] = $chem_unattempt;
      		$spr_data[$i]['chem_corr_percent'] = $chem_corr_percent;
      		$spr_data[$i]['chem_wrong_percent'] = $chem_wrong_percent;
      		$spr_data[$i]['chem_unattempt_percent'] = $chem_unattempt_percent;
      		$spr_data[$i]['chem_corr_accuracy'] = $chem_corr_accuracy;
      		$spr_data[$i]['chem_total_marks'] = $chem_total_marks;
          $spr_data[$i]['chem_marks_correct'] = $chem_marks_correct;
          $spr_data[$i]['chem_marks_wrong'] = $chem_marks_wrong;

      		if($paper_data['stream']== 'Eng'){
              // For Maths
          		$spr_data[$i]['maths_ques'] = $maths_ques;
          		$spr_data[$i]['maths_unattempt'] = $maths_unattempt;
          		$spr_data[$i]['maths_corr_percent'] = $maths_corr_percent;
          		$spr_data[$i]['maths_wrong_percent'] = $maths_wrong_percent;
          		$spr_data[$i]['maths_unattempt_percent'] = $maths_unattempt_percent;
          		$spr_data[$i]['maths_corr_accuracy'] = $maths_corr_accuracy;
          		$spr_data[$i]['maths_total_marks'] = $maths_total_marks;
              $spr_data[$i]['maths_marks_correct'] = $maths_marks_correct;
              $spr_data[$i]['maths_marks_wrong'] = $maths_marks_wrong;
          }
          
          if($paper_data['stream']== 'Med'){
          // For Biology
              $spr_data[$i]['bio_ques'] = $bio_ques;
              $spr_data[$i]['bio_unattempt'] = $bio_unattempt;
              $spr_data[$i]['bio_corr_percent'] = $bio_corr_percent;
              $spr_data[$i]['bio_wrong_percent'] = $bio_wrong_percent;
              $spr_data[$i]['bio_unattempt_percent'] = $bio_unattempt_percent;
              $spr_data[$i]['bio_corr_accuracy'] = $bio_corr_accuracy;
              $spr_data[$i]['bio_total_marks'] = $bio_total_marks;
              $spr_data[$i]['bio_marks_correct'] = $bio_marks_correct;
              $spr_data[$i]['bio_marks_wrong'] = $bio_marks_wrong;
          }


      	}
    }
    
	return($spr_data);

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

  return($res_max_spr);

  //echo "<pre>";
    //print_r($max_spr);
   
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

  return($res_avg_spr);

  //echo "<pre>";
    //print_r($avg_spr);
   
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



$link_id=connect_analysis_db();   			     // connection fn
$user=get_user_info($link_id);   			       // user_info
$paper_data=paper_info();   				         //Test Paper Info
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