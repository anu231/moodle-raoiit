<?php
//include('util.php');
include('db_connect.php');
include('active_batch.php');

require __DIR__ . '/vendor/autoload.php';


/*
    function for get all the active_user_info form batch
    arguments - $link_id(for connection), $key(active batch id)
    return - $user (userinfo of active batches)
    summary - takes active batch info & returns all active student of that batch.
*/
function get_batch_user_info($link_id,$key){

    $user = array();
    $sql="SELECT userid,username,mobilenumber,mobilefather,mobilemother,email,batch,ttbatchid FROM userinfo WHERE ttbatchid=$key AND status=1 AND isdummy=0";
    //$sql="SELECT userid,username,mobilenumber,mobilefather,mobilemother,email,batch,ttbatchid FROM userinfo WHERE userid=821285";
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

/*
    function for HTML-Email format
    arguments - $user(student from perticular batch),$user_spr(sudent performance data from get_user_spr) & $key(batch id of active batch)
    return - $format_spr_data (html table with user performance report of perticular paper id)  
    summary - takes user info & its performance data & returns html formatted data for email
*/

function format_email_spr_data($user,$user_spr,$key,$paper_data){
    include('config.php');
    $format_spr_data=NULL;
    $user_id=$user['userid'];
    $userbatch=$user['batch'];
    $username=$user['username'];
    
    $format_spr_data.="<style>";
    
    $format_spr_data.="
    .img-responsive {
        display:block;
        max-width:100%;
        height:auto;
        margin-left: auto;
        margin-right: auto;
    }
    .headerStyle 
    {
      color: #ffffff;
      font-size: 16px;
      text-align: center;
      font-weight: 500;
      background-color: #07889b;
    }

    .myTable tr:hover:not(.headerStyle):hover 
    {
      background-color: #bedcfc;
    }";
    
    $format_spr_data.="</style>";

    $format_spr_data.="<br/><br/>";
    
    
    /* Format data for Sr-engg/Jr-engg/Rep-engg/Sr-mains/Jr-mains/Rep-mains*/
    if ($userbatch==0 || $userbatch==1 || $userbatch==9 || $userbatch==10){
        $format_spr_data.="<table class='table table-bordered table-responsive table-condensed myTable' border=1  cellspacing=0>
                    <tr class='headerStyle' style='background-color: #07889b ;font-weight: bold;color:white; text-align:center;' >
                    <td >Test Name</td>";

                    if($paper_data['nphy']!=''){
                        $format_spr_data.="<td>Physics</td>";
                    }
                    if($paper_data['nchem']!=''){
                        $format_spr_data.="<td>Chemistry</td>";
                    }
                    if($paper_data['nmath']!=''){
                        $format_spr_data.="<td>Maths</td>";
                    }

                    $format_spr_data.="<td>Total</td><td>Rank</td><td>Detail Report</td>

                    </tr>";
        foreach ($user_spr as $key => $value){
            $userid=$user_id;
            $test_name=$user_spr[$key]['paper'];
            $paperid=$user_spr[$key]['paperid'];
            $papername=get_papername($paperid);
            $pobt=$user_spr[$key]['pobt'];
            $cobt=$user_spr[$key]['cobt'];
            $mobt=$user_spr[$key]['mobt'];
            $marksobt=$user_spr[$key]['marksobt'];
            $rank=$user_spr[$key]['rank'];

            $hash=md5($userid.'--'.$paperid);

            $format_spr_data.="<tr style='text-align:center;'>
            <td>$papername</td>";
            if($paper_data['nphy']!=''){
                $format_spr_data.="<td>$pobt</td>";
            }
            if($paper_data['nchem']!=''){
                $format_spr_data.="<td>$cobt</td>";
            }
            if($paper_data['nmath']!=''){
                $format_spr_data.="<td>$mobt</td>";
            }
            

            $format_spr_data.="<td>$marksobt</td><td>$rank</td><td><a href='$email_link?userid=$userid&paperid=$paperid&hashdata=$hash' class='btn btn-primary btn-lg active btn-sm' style='solid #000;text-decoration: none;' role='button' aria-pressed='true' target=_blank>Show Detail</a></td>";
        }

    }

    /* Format data for Sr-medical/Rep-Medical*/

    if ($userbatch==2 || $userbatch==3){
        $format_spr_data.="<table class='table table-bordered table-responsive table-condensed myTable' border=1  cellspacing=0>
                    <tr class='headerStyle' style='background-color: #07889b ;font-weight: bold;color:white; text-align:center;' >
                    <td>Test Name</td>";
                    
                if($paper_data['nphy']!=''){
                    $format_spr_data.="<td>Physics</td>";
                }
                if($paper_data['nchem']!=''){
                    $format_spr_data.="<td>Chemistry</td>";
                }
                if($paper_data['nbio']!=''){
                    $format_spr_data.="<td>Biology</td>";
                }

                $format_spr_data.="<td>Total</td><td>Rank</td><td>Detail Report</td>

                    </tr>";

        foreach ($user_spr as $key => $value){
            $userid=$user_id;
            $test_name=$user_spr[$key]['paper'];
            $paperid=$user_spr[$key]['paperid'];
            $papername=get_papername($paperid);
            $pobt=$user_spr[$key]['pobt'];
            $cobt=$user_spr[$key]['cobt'];
            $bobt=$user_spr[$key]['bobt'];
            $zobt=$user_spr[$key]['zobt'];
            $marksobt=$user_spr[$key]['marksobt'];
            $rank=$user_spr[$key]['rank'];
            $hash=md5($userid.'--'.$paperid);


            $format_spr_data.="<tr style='text-align:center;'>
            <td>$papername</td>";
            if($paper_data['nphy']!=''){
                $format_spr_data.="<td>$pobt</td>";
            }
            if($paper_data['nchem']!=''){
                $format_spr_data.="<td>$cobt</td>";
            }
            if($paper_data['nbio']!=''){
                $format_spr_data.="<td>$bobt</td>";
            }
            
            
            $format_spr_data.="<td>$marksobt</td>
            <td>$rank</td>
            <td><a href='$email_link?userid=$userid&paperid=$paperid&hashdata=$hash' class='btn btn-primary btn-lg active btn-sm' style='solid #000;text-decoration: none;' role='button' aria-pressed='true' target=_blank>Show Detail</a></td>";
        }
            
    }


    /* Format data for Prefoundation */

    if ($userbatch==4){
        $format_spr_data.="<table class='table table-bordered table-responsive table-condensed myTable' border=1  cellspacing=0>
                    <tr class='headerStyle' style='background-color: #07889b ;font-weight: bold;color:white; text-align:center;' >
                    <td >Test Name</td>";
            
            if($paper_data['nphy']!=''){
                $format_spr_data.="<td>Physics</td>";
            }
            
            if($paper_data['nchem']!=''){
                $format_spr_data.="<td>Chemistry</td>";
            }
            if($paper_data['nmath']!=''){
                $format_spr_data.="<td>Maths</td>";
            }
            if($paper_data['nbio']!=''){
                $format_spr_data.="<td>Biology</td>";
            }
            if($paper_data['nmat']!=''){
                $format_spr_data.="<td>Mental Ability</td>";
            }
            if($paper_data['neng']!=''){
                $format_spr_data.="<td>English</td>";
            }
            if($paper_data['nlr']!=''){
                $format_spr_data.="<td>Logical Reasoning</td>";
            }
            if($paper_data['nsoc']!=''){
                $format_spr_data.="<td>Social Science</td>";
            }
            if($paper_data['ncs']!=''){
                $format_spr_data.="<td>Computer Science</td>";
            }
            if($paper_data['ngk']!=''){
                $format_spr_data.="<td>General Knowledge</td>";
            }
            if($paper_data['nsci']!=''){
                $format_spr_data.="<td>Science</td>";
            }
    
            $format_spr_data.="<td>Total</td><td>Rank</td><td>Detail Report</td>

                    </tr>";
        foreach ($user_spr as $key => $value){
            $userid=$user_id;
            $test_name=$user_spr[$key]['paper'];
            $paperid=$user_spr[$key]['paperid'];
            $papername=get_papername($paperid);
            $pobt=$user_spr[$key]['pobt'];
            $cobt=$user_spr[$key]['cobt'];
            $mobt=$user_spr[$key]['mobt'];
            $bobt=$user_spr[$key]['bobt'];
            $maobt=$user_spr[$key]['maobt'];
            $eobt=$user_spr[$key]['eobt'];
            $lrobt=$user_spr[$key]['lrobt'];
            $ssobt=$user_spr[$key]['ssobt'];
            $csobt=$user_spr[$key]['csobt'];
            $gkobt=$user_spr[$key]['gkobt'];
            $scobt=$user_spr[$key]['scobt'];
            $marksobt=$user_spr[$key]['marksobt'];
            $rank=$user_spr[$key]['rank'];
            $hash=md5($userid.'--'.$paperid);

            $format_spr_data.="<tr style='text-align:center;'>
            <td>$papername</td>";
            if($paper_data['nphy']!=''){
                $format_spr_data.="<td>$pobt</td>";
            }
            if($paper_data['nchem']!=''){
                $format_spr_data.="<td>$cobt</td>";
            }
            if($paper_data['nmath']!=''){
                $format_spr_data.="<td>$mobt</td>";
            }
            if($paper_data['nbio']!=''){
                $format_spr_data.="<td>$bobt</td>";
            }
            if($paper_data['nmat']!=''){
                $format_spr_data.="<td>$maobt</td>";
            }
            if($paper_data['neng']!=''){
                $format_spr_data.="<td>$eobt</td>";
            }
            if($paper_data['nlr']!=''){
                $format_spr_data.="<td>$lrobt</td>";
            }
            if($paper_data['nsoc']!=''){
                $format_spr_data.="<td>$ssobt</td>";
            }
            if($paper_data['ncs']!=''){
                $format_spr_data.="<td>$csobt</td>";
            }
            if($paper_data['ngk']!=''){
                $format_spr_data.="<td>$gkobt</td>";
            }
            if($paper_data['nsci']!=''){
                $format_spr_data.="<td>$scobt</td>";
            }
            
            $format_spr_data.="<td>$marksobt</td>
            <td>$rank</td>
            <td><a href='$email_link?userid=$userid&paperid=$paperid&hashdata=$hash' class='btn btn-primary btn-lg active btn-sm' style='solid #000;text-decoration: none;' role='button' aria-pressed='true' target=_blank>Show Detail</a></td>";
        }

            
    }

    $format_spr_data.="</tr></table><br>";
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

    $user_emails=$user['email'];
    //$user_emails='pandurang.aglave@raoiit.com';
    $user_emails = ltrim($user_emails, ',');
    $user_emails = rtrim($user_emails, ',');
    $user_email_id=explode(",", $user_emails);
    $email_id=array_unique($user_email_id);
    $email_message= "<table class='table table-bordered table-responsive table-condensed' border=1  cellspacing=0 ><tr><td><img src='$email_img_link/Rao-IIT-Full-New-website-Logo.png' class='img-responsive'/></td></tr><tr><td><br/>
    Dear $user[username],<br/><br/>
    
    $format_spr_data           
    <br><br>


    -------------<br>
    Thanks & Regards<br>
    Rao IIT Academy
    <br/><br/>

    <center><u><b>NOTE:This is a system generated mail.Please do not reply to this email ID.</b></u></center>
    <center><u><i>Please do not print this mail until & unless required.</i></u></center>
    <br/>
    </td></tr></table>";
    
    //echo $email_message."<br/>";
    //echo "";
    $from = new SendGrid\Email("Rao IIT Notification", "sg@raoiit.com");
    $subject = "Performance Report - Rao IIT Academy";

     //Start----> For Single User
    //$to = new SendGrid\Email($userlist['username'], $user_email_id[0]);       // primary email-id of student
    /*
    $to = new SendGrid\Email($user['username'], "pandurang.aglave@raoiit.com");       // primary email-id of student
    
    $content = new SendGrid\Content("text/html",$email_message);
    $mail = new SendGrid\Mail($from, $subject, $to, $content);
    */
    //For Single User-------->End
  

    //Start------> For Multiple email ids
    echo PHP_EOL;
    echo 'Sending Email to : '.$user['username'].' - Roll No - '.$user['userid'].PHP_EOL;

    $to = new SendGrid\Email($user['username'], $email_id[0]);       // primary email-id of student
    $content = new SendGrid\Content("text/html",$email_message);
    $mail = new SendGrid\Mail($from, $subject, $to, $content);
    echo 'Email ID - '.$user_email_id[0].PHP_EOL;

    for($i=1;$i<count($user_email_id);$i++){
        if(@$email_id[$i]==''){
            continue;
        }
        $email[$i+2] = new SendGrid\Email($user['username'], $email_id[$i]);                // extract email id if more than one
        $mail->personalization[0]->addTo($email[$i+2]);
        echo 'Email ID - '.@$user_email_id[$i].PHP_EOL;
    }

    /*for($i=0;$i<count($user_email_id)-1;$i++){                  
        $email[$i+2] = new SendGrid\Email($user['username'], $user_email_id[$i+1]);                // extract email id if more than one
        $mail->personalization[0]->addTo($email[$i+2]);
        echo 'Email ID - '.$user_email_id[$i+1].PHP_EOL;
    }*/


    
    //For multiple email ids--------End


    $apiKey = "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";
    $sg = new \SendGrid($apiKey);

    $response = $sg->client->mail()->send()->post($mail);
    $res_code=$response->statusCode();
    $status_info=implode(" ",$response->headers());
    
    $csv_data[]=array($user['userid'],$user['username'],$user['email'],$res_code,$status_info);

    foreach ($csv_data as $row)
    {
        fputcsv($file_name, $row);
    }

    echo $response->statusCode().PHP_EOL;

    

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
//$active_batch=array("1204"=>"JPS1");                  //For Engineering Branches
foreach ($active_batch as $key => $value){
    
    $user=get_batch_user_info($link_id,$key);   // Userinfo of current batch
    $paper_data=get_paperinfo($paperid);
    $paper_date=substr($paper_data['startdate'],0,10);
    $max = sizeof($user);
    echo 'Total Users belonging to this batch - '.$max.PHP_EOL;
    // Insert all the data into csv to save the complete info of 
    $file_name='/media/E_Drive/SPR-REPORTS/'.$paperid.'_EMAIL_'.$paper_date.'.csv';
    if(file_exists($file_name)){
        $file_name = fopen($file_name, 'a');
    }
    else{
        $file_name = fopen($file_name, 'w');
        fputcsv($file_name, array('Roll No', 'Student Name', 'Email Id', 'Status', 'Status Detail'));
    }
    
    for($i = 0; $i < $max;$i++){
        
        $user_spr=get_user_spr($user[$i]);      // get student performance report
        //echo 'Formatted Email - '.PHP_EOL;
        if(empty($user_spr) || $user_spr=="USER DNE" || ($user_spr[0]['pobt']==0 && $user_spr[0]['cobt']==0 && $user_spr[0]['mobt']==0 && $user_spr[0]['bobt']==0)){
            continue;
        }
        //echo $user[$i]['userid'].PHP_EOL;
        $format_spr_data=format_email_spr_data($user[$i],$user_spr,$key,$paper_data);     // Email format
        email_report($user[$i],$user_spr,$format_spr_data,$file_name);      // sends email to user 
        //break;
    }
}

close_analysis_db($link_id);         // close database connection
?>
