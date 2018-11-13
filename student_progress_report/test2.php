<?php

$total_corr=NULL;
	$loginUrl = "https://portal.raoiit.com/student/spr?auth=v1Bdyp&username=807464&pid=768";   // cURL to get student performance info for perticular paper_id
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
	

	echo "<pre>";
	

$paper_info = "https://portal.raoiit.com/student/pinfo?pid=768";   // cURL for getting test paper info
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
	//print_r($paper_data);
	
		//echo $calculatedData;
 	$spr[]="";
  	

  $subject = array( "p", "c", "m", "b", "z", "ma", "e", "lr", "ss", "cs", "gk", "sc" );
  $subjectdata = array( "corr", "wrong", "obt", "negmarks");
  for($i=0;$i<count($subjectdata);$i++)
  {
    for($j=0;$j<count($subject);$j++)
    {
      $spr['total'.$subjectdata[$i]]+=$spr_data[0][$subject[$j].$subjectdata[$i]];
      $spr[$subject[$j].$subjectdata[$i]]+=$spr_data[0][$subject[$j].$subjectdata[$i]];
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
  
  $subject = array( "p", "c", "m", "b" );
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
  for($i=0;$i<count($printSubject);$i++)
  {
    for($j=0;$j<count($subject);$j++)
    {
      
      
      if($spr[$subject[$j].$subjectdata[$i]]!=0){
      	$spr['subject'.$i]=$subject[$j].$subjectdata[$i];
      	$spr['forsubject'.$i]=$printSubject[$i];
      	$spr['sub'.$i]=$subject1[$i];
      	$count=$count+1;
      }
    }
    $spr['forCount']=$count;
  }

print_r($spr);

	//echo $myJSON['total_quesntot'];
	//echo $spr_data_data[0]['negmarks'];
	//echo $spr_data['totalcorr'];

/*
			$p=$paper_subjects[$i].'_ques';

	        //$spr_data_data[unattempt]=$$p-($spr_data_data[0][$subject[$i].'corr']+$spr_data_data[0][$subject[$i].'wrong']);
	        echo "<pre>";
	       	print_r($spr_data_data);
	        
	        if($spr_data_data[0][$subject[$i].'obt']==0 && $spr_data_data[0][$subject[$i].'corr']==0 && $spr_data_data[0][$subject[$i].'wrong']==0 && $spr_data_data[0][$subject[$i].'negmarks']==0){
	        	$corrAccuracy='null';
	        	$corrPercent='null';
	        	$wrongPercent='null';
	        }
	        else{
	        	$corrAccuracy+=round((($spr_data_data[0][$subject[$i].'corr']*100)/($spr_data_data[0][$subject[$i].'corr']+$spr_data_data[0][$subject[$i].'wrong'])),1);
	        	$corrPercent+=round((($spr_data_data[0][$subject[$i].'corr']*100)/$$p),1);
	        	$wrongPercent+=round((($spr_data_data[0][$subject[$i].'wrong']*100)/$$p),1);
	        	
	        }
	        $unattemptPercent+=round((($unattempt*100)/$$p),1);
	        $marksCorrect+=$spr_data_data[0][$subject[$i].'obt']+$spr_data_data[0][$subject[$i].'negmarks'];
	        
	    
	    
		array_push($dataResult,$calculatedData,$unattempt,$corrAccuracy,$corrPercent,$wrongPercent,$unattemptPercent,$marksCorrect);
		$calculatedData=0;
		$unattempt=0;
		$corrAccuracy=0;
		$corrPercent=0;
		$wrongPercent=0;
		$unattemptPercent=0;
		$marksCorrect=0;
	}
	echo "<pre>";
	print_r($dataResult);


	$subject1 = array( "phy", "chem", "maths", "bio" );
    $var_subject = array( "_ques", "_unattempt", "_corr_percent", "_wrong_percent", "_unattempt_percent", "_corr_accuracy", "_total_marks", "_marks_obtained", "_marks_correct", "_marks_wrong" );

    for($i=0;$i<count($subject1);$i++)
	{
		for($j=0;$j<count($var_subject);$j++)
		{
			$p="$".$subject1[$i].$var_subject[$j];
			$$p="NULL";
			echo $$p."<br/>";
		}
		echo "<br/>";
	}
*/
	?>