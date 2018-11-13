<?php
//include('util.php');
include('db_connect.php');
include('active_batch.php');

//require __DIR__ . '/vendor/autoload.php';

/*
    function for get all the active_user_info form batch
    arguments - $link_id(for connection), $key(active batch id)
    return - $user (userinfo of active batches)
    summary - takes active batch info & returns all active student of that batch.
*/
function get_batch_user_info($link_id,$key){

    $user = array();
    $sql="SELECT userid,username,mobilenumber,mobilefather,mobilemother,email,batch,ttbatchid FROM userinfo WHERE ttbatchid=$key AND status=1 AND isdummy=0";
    $result=mysqli_query($link_id,$sql);
    if(mysqli_num_rows($result) > 0 ){
        while ($row=mysqli_fetch_assoc($result)){
            $user[]= $row;
        }
    }
    //echo "<pre>";
    //print_r($user);
    return $user;
}


/*
    function for get student performance report 
    arguments - $user(student from perticular batch)
    return - $spr_data (student performance report of active batche student & perticular paperid)  
    summary - takes user info & returns performance report data of perticular user
*/

function get_user_spr($user){
    include('config.php');

    $userid = $user['userid'];
    $loginUrl = $spr_cURL."username=$userid&pid=$paperid";
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

    return $spr_data;

}

/*
    function for get Paper info of paperid(pid)
    parameters - 
    return - $paper_data (paperinfo of paperid)  
    summary - Takes paperid(pid) in cURL & returns all the info of that paper id
*/


function get_papername($paperid){
    include('config.php');
    $papername = NULL;
    
    $paper_info = $paper_info_cURL."pid=$paperid";   // cURL for getting test paper info
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
    $papername=$paper_data['name'];
  
  return($papername);
}

function get_paperinfo($paperid){
    include('config.php');
    
    $paper_info = $paper_info_cURL."pid=$paperid";   // cURL for getting test paper info
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
    
  return($paper_data);
}

//gets the data from a cURL  
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




/*
    function for SMS format
    arguments - $user(student from perticular batch),$user_spr(sudent performance data from get_user_spr) & $key(batch id of active batch)
    return - $format_spr_data (html table with user performance report of perticular paper id)  
    summary - takes user info & its performance data & returns html formatted data for email
*/

function format_sms_spr_data($user,$user_spr,$key,$paper_data){
    include('config.php');
    $format_spr_data=NULL;
    $user_id=$user['userid'];
    $userbatch=$user['batch'];
    $username=$user['username'];
    
    
    /* Format data for Sr-engg/Jr-engg/Rep-engg/Sr-mains/Jr-mains/Rep-mains*/
    if ($userbatch==0 || $userbatch==1 || $userbatch==9 || $userbatch==10){
        
        foreach ($user_spr as $key => $value){
            $userid=$user_id;
            $test_name=$user_spr[$key]['paper'];
            $paperid=$user_spr[$key]['paperid'];
            $papername=get_papername($paperid);
            $pobt=round($user_spr[$key]['pobt'],0);
            $cobt=round($user_spr[$key]['cobt'],0);
            $mobt=round($user_spr[$key]['mobt'],0);
            $marksobt=round($user_spr[$key]['marksobt'],0);
            $rank=$user_spr[$key]['rank'];

            $hash=md5($userid.'--'.$paperid);

            $paper_name=explode("/",$test_name);  

            $long_url= "$email_link?userid=$userid&paperid=$paperid&hashdata=$hash";
            $short_Url=get_tiny_url($long_url);
            $format_spr_data="Your performance report\nTest: $papername\nTotal: $marksobt";

            if($paper_data['nphy']!=''){
                $pobt=round($user_spr[$key]['pobt'],0);
                $format_spr_data.="\nP: $pobt";
            }
            if($paper_data['nchem']!=''){
                $cobt=round($user_spr[$key]['cobt'],0);
                $format_spr_data.="\nC: $cobt";
            }
            if($paper_data['nmath']!=''){
                $mobt=round($user_spr[$key]['mobt'],0);
                $format_spr_data.="\nM: $mobt";
            }
            
            $format_spr_data.="\nDetailed Report: $short_Url";
            
        }

    }

    /* Format data for Sr-medical/Rep-Medical*/

    if ($userbatch==2 || $userbatch==3){
        
        foreach ($user_spr as $key => $value){
            $userid=$user_id;
            $test_name=$user_spr[$key]['paper'];
            $paperid=$user_spr[$key]['paperid'];
            $papername=get_papername($paperid);
            $pobt=round($user_spr[$key]['pobt'],0);
            $cobt=round($user_spr[$key]['cobt'],0);
            $bobt=round($user_spr[$key]['bobt'],0);
            $zobt=round($user_spr[$key]['zobt'],0);
            $marksobt=round($user_spr[$key]['marksobt'],0);
            $rank=$user_spr[$key]['rank'];
            $hash=md5($userid.'--'.$paperid);
            $paper_name=explode("/",$test_name);  

            $long_url= "$email_link?userid=$userid&paperid=$paperid&hashdata=$hash";
            $short_Url=get_tiny_url($long_url);
            $format_spr_data="Your performance report\nTest: $papername\nTotal: $marksobt";
            
            if($paper_data['nphy']!=''){
                $pobt=round($user_spr[$key]['pobt'],0);
                $format_spr_data.="\nP: $pobt";
            }
            if($paper_data['nchem']!=''){
                $cobt=round($user_spr[$key]['cobt'],0);
                $format_spr_data.="\nC: $cobt";
            }
            if($paper_data['nbio']!=''){
                $bobt=round($user_spr[$key]['bobt'],0);
                $format_spr_data.="\nB: $bobt";
            }
            $format_spr_data.="\nDetailed Report: $short_Url";
        }
            
    }


    /* Format data for Prefoundation */

    if ($userbatch==4){
        
        foreach ($user_spr as $key => $value){
            $userid=$user_id;
            $test_name=$user_spr[$key]['paper'];
            $paperid=$user_spr[$key]['paperid'];
            $papername=get_papername($paperid);
            $pobt=round($user_spr[$key]['pobt'],0);
            $cobt=round($user_spr[$key]['cobt'],0);
            $bobt=round($user_spr[$key]['mobt'],0);
            $zobt=round($user_spr[$key]['bobt'],0);
            $pobt=round($user_spr[$key]['maobt'],0);
            $cobt=round($user_spr[$key]['eobt'],0);
            $bobt=round($user_spr[$key]['lrobt'],0);
            $zobt=round($user_spr[$key]['ssobt'],0);
            $pobt=round($user_spr[$key]['csobt'],0);
            $cobt=round($user_spr[$key]['gkobt'],0);
            $bobt=round($user_spr[$key]['scobt'],0);

            $marksobt=round($user_spr[$key]['marksobt'],0);
            
            $rank=$user_spr[$key]['rank'];
            $hash=md5($userid.'--'.$paperid);
            $paper_name=explode("/",$test_name);  

            $long_url= "$email_link?userid=$userid&paperid=$paperid&hashdata=$hash";
            $short_Url=get_tiny_url($long_url);
            $format_spr_data="Your performance report\nTest: $papername\nTotal: $marksobt";

            if($paper_data['nphy']!=''){
                $pobt=round($user_spr[$key]['pobt'],0);
                $format_spr_data.="\nP: $pobt";
            }
            if($paper_data['nchem']!=''){
                $cobt=round($user_spr[$key]['cobt'],0);
                $format_spr_data.="\nC: $cobt";
            }
            if($paper_data['nmath']!=''){
                $mobt=round($user_spr[$key]['mobt'],0);
                $format_spr_data.="\nM: $mobt";
            }
            if($paper_data['nbio']!=''){
                $bobt=round($user_spr[$key]['bobt'],0);
                $format_spr_data.="\nB: $bobt";
            }
            if($paper_data['nmat']!=''){
                $maobt=round($user_spr[$key]['maobt'],0);
                $format_spr_data.="\nMA: $maobt";
            }
            if($paper_data['neng']!=''){
                $eobt=round($user_spr[$key]['eobt'],0);
                $format_spr_data.="\nE: $eobt";
            }
            if($paper_data['nlr']!=''){
                $lrobt=round($user_spr[$key]['lrobt'],0);
                $format_spr_data.="\nLR: $lrobt";
            }
            if($paper_data['nsoc']!=''){
                $ssobt=round($user_spr[$key]['ssobt'],0);
                $format_spr_data.="\nSS: $ssobt";
            }
            if($paper_data['ncs']!=''){
                $csobt=round($user_spr[$key]['csobt'],0);
                $format_spr_data.="\nCS: $csobt";
            }
            if($paper_data['ngk']!=''){
                $gkobt=round($user_spr[$key]['gkobt'],0);
                $format_spr_data.="\nGK: $gkobt";
            }
            if($paper_data['nsci']!=''){
                $scobt=round($user_spr[$key]['scobt'],0);
                $format_spr_data.="\nSC: $scobt";
            }
            $format_spr_data.="\nDetailed Report: $short_Url";
        }

            
    }
    //echo $format_spr_data;

    return $format_spr_data;

}




/*
    function for sending email
    arguments - $user(all users with perticular batch), $format_topic_complete_data(complete format for email data)
    return - NULL 
    summary - sends email to user using SendGrid\Mail
*/
function email_report($user,$user_spr,$format_spr_data,$file_name){
    include('config.php');
    $user_name=explode(" ", $user['username']);
    $sms_message="Dear $user_name[0],\n$format_spr_data\n";
    //$sms_message= "Dear $user_name[0],$format_spr_data";
    
    //echo $sms_message."<br/>";
    /*$student_mobile=9702186209;
    $father_mobile=9702186209;
    $mother_mobile=9702186209;*/
    $student_mobile=$user['mobilenumber'];
    $father_mobile=$user['mobilefather'];
    $mother_mobile=$user['mobilemother'];
    
    echo PHP_EOL;
    echo 'Sending SMS to : '.$user['username'].' - Roll No - '.$user['userid'].PHP_EOL;

    if($student_mobile!='' && $student_mobile!='NULL'){
        echo 'Student Mobile - '.$student_mobile.PHP_EOL;
        $status_info1=sendSMS($student_mobile, $sms_message);        //Function call for sending sms
        
    }
    else{
        $student_mobile=0;
        $status_info1=0;
    }


    if($father_mobile!='' && $father_mobile!='NULL' && $father_mobile!=$student_mobile){
        echo 'Father Mobile - '.$father_mobile.PHP_EOL;
        $status_info2=sendSMS($father_mobile, $sms_message);        //Function call for sending sms
        
    }
    else{
        $father_mobile=0;
        $status_info2=0;
    }

    if($mother_mobile!='' && $mother_mobile!='NULL' && $mother_mobile!=$student_mobile && $mother_mobile!=$father_mobile){
        echo 'Mother Mobile - '.$mother_mobile.PHP_EOL;
        $status_info3=sendSMS($mother_mobile, $sms_message);        //Function call for sending sms
        
    }
    else{
        $mother_mobile=0;
        $status_info3=0;
    }

    $csv_data[]=array($user['userid'],$user['username'],$student_mobile,$status_info1,$father_mobile,$status_info2,$mother_mobile,$status_info3);
    foreach ($csv_data as $row)
    {
        fputcsv($file_name, $row);
    }

    
}

function sendSMS(&$s_mobile, &$s_text)
{
    global $errors, $success;

    //initialize the request variable
    $request = "";
    //this is the key of our sms account
    $param["workingkey"] = "3693f2jl9yh7375b0o1i";//"174361n8xjqd2t60247";
    //this is the message that we want to send
    $param["message"] = stripslashes($s_text);
    //these are the recipients of the message
    $param["to"] = $s_mobile;
    //this is our sender
    $param["sender"] = "RAOIIT";//"BULKSMS";

    //traverse through each member of the param array
    foreach($param as $key=>$val){
        //we have to urlencode the values
        $request.= $key."=".urlencode($val);
        //append the ampersand (&) sign after each paramter/value pair
        $request.= "&";
    }
    //remove the final ampersand sign from the request
    $request = substr($request, 0, strlen($request)-1);

    //this is the url of the gateway's interface
    //$url = "http://promo.chandnas.com/promoservice/api/web2sms.php?$request";
    //$url = "http://alerts.prioritysms.com/api/web2sms.php?$request";
    $url = "http://alerts.prioritysms.com/api/web2sms.php";

    //echo $url;

    //initialize curl handle
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); //set the url
    curl_setopt($ch, CURLOPT_POST, count($param)); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $request); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //return as a variable
    $response = curl_exec($ch); //run the whole process and return the response
    curl_close($ch); //close the curl handle

    $responseary = explode("Invalid/DND Numbers:", $response);

    if (substr(trim($responseary[0]), 0, 12) == "Message GID=") // delivered successfully
    {
        $successary = explode(",", trim($responseary[0]));
        $success .= "SMS successfully delivered to " . sizeof($successary) . " mobile numbers.";
    }
    else if (strlen(trim($responseary[1])) != 0) // errors
    {
        $errors .= "Invalid/DND Numbers: " . trim($responseary[1]);
    }

    $status_info=implode(" ",$responseary);
    return $status_info;

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


include('config.php');
$link_id=connect_analysis_db();    // Database Connection 
$active_batch=get_active_batches($link_id);         // All active batches
//$active_batch=array("1204"=>"JPS1");

foreach ($active_batch as $key => $value){
    
    $user=get_batch_user_info($link_id,$key);   // Userinfo of current batch
    $paper_data=get_paperinfo($paperid);
    $paper_date=substr($paper_data['startdate'],0,10);
    $max = sizeof($user);
    echo 'Total Users belonging to this batch - '.$max.PHP_EOL;

    // Insert all the data into csv to save the complete info of 
    $file_name='/media/E_Drive/SPR-REPORTS/'.$paperid.'_SMS_'.$paper_date.'.csv';
    if(file_exists($file_name)){
        $file_name = fopen($file_name, 'a');
    }
    else{
        $file_name = fopen($file_name, 'w');
        fputcsv($file_name, array('Roll No', 'Student Name', 'Student Mobile','Student SMS Status', 'Father Mobile','Father SMS Status', 'Mother Mobile' ,'Mother SMS Status'));
    }
    
    for($i = 0; $i < $max;$i++){
        //echo $user[$i]['userid'];
        $user_spr=get_user_spr($user[$i]);      // get student performance report
        //echo 'Formatted sms - '.PHP_EOL;
        if(empty($user_spr) || $user_spr=="USER DNE" || ($user_spr[0]['pobt']==0 && $user_spr[0]['cobt']==0 && $user_spr[0]['mobt']==0 && $user_spr[0]['bobt']==0)){
            continue;
        }
        $format_spr_data=format_sms_spr_data($user[$i],$user_spr,$key,$paper_data);     // sms format
        email_report($user[$i],$user_spr,$format_spr_data,$file_name);      // sends sms to user 
        //break;
    }

    
}



close_analysis_db($link_id);         // close database connection

?>
