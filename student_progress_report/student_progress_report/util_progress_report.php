<?php
//include('util.php');
include('db_connect.php');
include('active_batch.php');

require __DIR__ . '/vendor/autoload.php';



echo "<link href='css/bootstrap.css' rel='stylesheet'>  </style>";


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
    function for HTML-Email format
    arguments - $user(student from perticular batch),$user_spr(sudent performance data from get_user_spr) & $key(batch id of active batch)
    return - $format_spr_data (html table with user performance report of perticular paper id)  
    summary - takes user info & its performance data & returns html formatted data for email
*/

function format_email_spr_data($user,$user_spr,$key){
    include('config.php');
    $format_spr_data=NULL;
    $user_id=$user['userid'];
    $userbatch=$user['batch'];
    $username=$user['username'];
    $format_spr_data.="<style>";
    
    $format_spr_data.=".phytb4 {
        background-color: #8dde61 ;
        font-weight: bold;
        color:#26500f;
    }
    .chemtb4 {
        background-color: #fbd1f5 ;
        font-weight: bold;
        color:#b1259c;
    }
    .mathtb4 {
        background-color: #f8d8a0 ;
        font-weight: bold;
        color:#d55b12;
    }
    .botanyb4 {
        background-color: #d9f7f0 ;
        font-weight: bold;
        color:#5d9b8d;
    }
    .zootb4 {
        background-color: #f9e0e0 ;
        font-weight: bold;
        color:#a83434;
    }
    .matb4 {
        background-color: #8dde61 ;
        font-weight: bold;
        color:#26500f;
    }
    .engtb4 {
        background-color: #fbd1f5 ;
        font-weight: bold;
        color:#b1259c;
    }
    .langmtb4 {
        background-color: #f8d8a0 ;
        font-weight: bold;
        color:#d55b12;
    }
    .sstb4 {
        background-color: #d9f7f0 ;
        font-weight: bold;
        color:#5d9b8d;
    }
    .cstb4 {
        background-color: #f9e0e0 ;
        font-weight: bold;
        color:#a83434;
    }
    .gktb4 {
        background-color: #8dde61 ;
        font-weight: bold;
        color:#26500f;
    }
    .sctb4 {
        background-color: #fbd1f5 ;
        font-weight: bold;
        color:#b1259c;
    }
    .totaltb4 {
        background-color: #e6f9ff ;
        font-weight: bold;
        color:#223d8c;
    }
    .ranktb4 {
        background-color: #fbffd5 ;
        font-weight: bold;
        color:#64590c;
    }
    .report {
        background-color: #9fcffc ;
        font-weight: bold;
        color:#00172d;
    }
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
                    <td >Test Name</td><td>Physics</td><td>Chemistry</td><td>Maths</td><td>Total</td><td>Rank</td><td>Detail Report</td>
                </tr>";
        foreach ($user_spr as $key => $value){
            $userid=$user_id;
            //$userid=806849;
            
            $test_name=$user_spr[$key]['paper'];
            $paperid=$user_spr[$key]['paperid'];
            $pobt=$user_spr[$key]['pobt'];
            $cobt=$user_spr[$key]['cobt'];
            $mobt=$user_spr[$key]['mobt'];
            $marksobt=$user_spr[$key]['marksobt'];
            $rank=$user_spr[$key]['rank'];

            $hash=md5($userid.'--'.$paperid);
            $format_spr_data.="<tr style='text-align:center;height:10px;'><td>$test_name</td><td>$pobt</td><td>$cobt</td><td>$mobt</td><td>$marksobt</td><td>$rank</td><td><a href='$email_link?userid=$userid&paperid=$paperid&hashdata=$hash' class='btn btn-primary btn-lg active btn-sm' style='solid #000;background-color:#1e88e5;color:#fff;text-decoration: none;' role='button' aria-pressed='true' target=_blank>Show Detail</a></td>";
        }

    }

    /* Format data for Sr-medical/Rep-Medical*/

    if ($userbatch==2 || $userbatch==3){
        $format_spr_data.="<table class='table table-bordered table-responsive table-condensed myTable' border=1  cellspacing=0>
                    <tr class='headerStyle' style='background-color: #07889b ;font-weight: bold;color:white; text-align:center;' >
                    <td>Test Name</td><td class=phytb4>Physics</td><td class=chemtb4>Chemistry</td><td class=botanyb4>Botany</td><td class=zootb4>Zoology</td><td class=totaltb4>Total</td><td colspan=5 class=ranktb4>Rank</td>
                    </tr>";
        foreach ($user_spr as $key => $value){
            $userid=$user_spr[$key]['userid'];
            $test_name=$user_spr[$key]['name'];
            $pobt=$user_spr[$key]['pobt'];
            $cobt=$user_spr[$key]['cobt'];
            $bobt=$user_spr[$key]['bobt'];
            $zobt=$user_spr[$key]['zobt'];
            $marksobt=$user_spr[$key]['marksobt'];
            $rank=$user_spr[$key]['rank'];
            $format_spr_data.="<tr style='text-align:center;'><td>$test_name</td><td>$pobt</td><td>$cobt</td><td>$bobt</td><td>$zobt</td><td>$marksobt</td><td>$rank</td>";
        }
            
    }


    /* Format data for Prefoundation */

    if ($userbatch==4){
        $format_spr_data.="<table class='table table-bordered table-responsive table-condensed myTable' border=1  cellspacing=0>
                    <tr class='headerStyle' style='background-color: #07889b ;font-weight: bold;color:white; text-align:center;' >
                    <td>Test Name</td>
                    <td class=phytb4>Physics</td>
                    <td class=chemtb4>Chemistry</td>
                    <td class=mathtb4>Maths</td>
                    <td class=engtb4>English</td>
                    <td class=sctb4>Sci</td>
                    <td class=botanyb4>Botany</td>
                    <td class=zootb4>Zoology</td>
                    <td class=matb4>Mentalability</td>
                    
                    <td class=totaltb4>Total</td>
                    <td class=ranktb4>Rank</td>
                    </tr>";
        foreach ($user_spr as $key => $value){
            $userid=$user_spr[$key]['userid'];
            $test_name=$user_spr[$key]['name'];
            $pobt=$user_spr[$key]['pobt'];
            $cobt=$user_spr[$key]['cobt'];
            $mobt=$user_spr[$key]['mobt'];
            $eobt=$user_spr[$key]['eobt'];
            $sobt=$user_spr[$key]['sobt'];
            $bobt=$user_spr[$key]['bobt'];
            $zobt=$user_spr[$key]['zobt'];
            $yobt=$user_spr[$key]['yobt'];
            
            $marksobt=$user_spr[$key]['marksobt'];
            
            $rank=$user_spr[$key]['rank'];

            $format_spr_data.="<tr style='text-align:center;'>
            <td>$test_name</td>
            <td>$pobt</td>
            <td>$cobt</td>
            <td>$mobt</td>
            <td>$eobt</td>
            <td>$sobt</td>
            <td>$bobt</td>
            <td>$zobt</td>
            <td>$yobt</td>
            
            <td>$marksobt</td>
            <td>$rank</td>";
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
function email_report($user,$user_spr,$format_spr_data){
    include('config.php');
    if(!empty($user_spr)){    
    $user_emails=$user['email'];

    $user_email_id=explode(",", $user_emails);
    echo  "
    ";
    $email_message= "<table class='table table-bordered table-responsive table-condensed' border=1  cellspacing=0 ><tr><td><img src='$email_img_link/Rao-IIT-Full-New-website-Logo.png' class='img-responsive'/></td></tr><tr><td><br/>
    Dear $user[username],<br/><br/>
    
    <u><b>NOTE:This is a system generated mail.Please do not reply to this email ID.</b></u>
                
    $format_spr_data           
    <br><br>


    -------------<br>
    Thanks & Regards<br>
    Rao IIT Academy
    </td></tr></table>";
    
    echo $email_message."<br/>";
    echo "";
    $from = new SendGrid\Email("Rao IIT Notification", "sg@raoiit.com");
    $subject = "Performance Report - Rao IIT Academy";

     //Start----> For Single User
    //$to = new SendGrid\Email($userlist['username'], $user_email_id[0]);       // primary email-id of student
    $to = new SendGrid\Email($user['username'], "anurag.sharma@raoiit.com");       // primary email-id of student
    //$content = new SendGrid\Content("text/html",$email_message);
    //$mail = new SendGrid\Mail($from, $subject, $to, $content);

    //For Single User-------->End
  

    //Start------> For Multiple email ids
    /*
    $to = new SendGrid\Email($userlist['username'], $user_email_id[0]);       // primary email-id of student
    $content = new SendGrid\Content("text/html",$email_message);
    $mail = new SendGrid\Mail($from, $subject, $to, $content);
    for($i=0;$i<count($user_email_id)-1;$i++){                  
        $email[$i+2] = new SendGrid\Email($userlist['username'], $user_email_id[$i+1]);                // extract email id if more than one
        $mail->personalization[0]->addTo($email[$i+2]);
    }
    */

    //For multiple email ids--------End
    $apiKey = "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";
    $sg = new \SendGrid($apiKey);

    $response = $sg->client->mail()->send()->post($mail);
    echo PHP_EOL;
    echo 'Sending Email to : '.$user['username'].PHP_EOL;
    echo $response->statusCode().PHP_EOL;
    
  }

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


$link_id=connect_analysis_db();    // Database Connection 
//$active_batch=get_active_batches($link_id);         // All active batches
$active_batch=array("760"=>"ARJ1");
foreach ($active_batch as $key => $value){
    
    $user=get_batch_user_info($link_id,$key);   // Userinfo of current batch
    $max = sizeof($user);
    //echo 'Total Users belonging to this batch - '.$max.PHP_EOL;
    for($i = 0; $i < $max;$i++){
        //echo $user[$i]['userid'];
        $user_spr=get_user_spr($user[$i]);      // get student performance report
        //echo 'Formatted Email - '.PHP_EOL;
        $format_spr_data=format_email_spr_data($user[$i],$user_spr,$key);     // Email format
        email_report($user[$i],$user_spr,$format_spr_data);      // sends email to user 
        break;
    }
    
}



close_analysis_db($link_id);         // close database connection

?>
