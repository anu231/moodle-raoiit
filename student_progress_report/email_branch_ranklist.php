<?php
namespace SendGrid;
include('config.php');
include('db_connect.php');
include('active_centres.php');

require __DIR__ . '/vendor/autoload.php';


function get_centre_email($link_id,$key){
    include('config.php');
    $email = NULL;
    $sql="SELECT email FROM centreinfo WHERE id=$key AND status=1";
    $result=mysqli_query($link_id,$sql);
    if(mysqli_num_rows($result) > 0 ){
        while ($row=mysqli_fetch_assoc($result)){
                $email= $row['email'];
        }
    }
    //echo "<pre>";
    //print_r($user);
    return $email;
}
/*
    function for get all the active_user_info form centre
    arguments - $link_id(for connection), $key(active batch id)
    return - $user (userinfo of active batches)
    summary - takes active batch info & returns all active student of that batch.
*/
function get_centre_user_info($link_id,$key){
    include('config.php');
    
    $user = array();
    $sql="SELECT userid,username,mobilenumber,mobilefather,mobilemother,email,targetyear,batch,ttbatchid FROM userinfo WHERE centre=$key AND targetyear=$target AND batch IN ($batch) AND status=1 AND isdummy=0";
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

function output_data($user,$user_spr,$paper_data,$file_name,$value,$key){

    //echo "<pre>";
    //print_r($user_spr);
    $paper_name = NULL;
    $stream = NULL;
    $result_data = array();
    $centre_name = NULL;
    $roll_no = NULL;

    $phy_tot_right = NULL;
    $phy_tot_wrong = NULL;
    $phy_tot_left = NULL;
    $phy_tot_loss = NULL;
    $phy_tot_marks = NULL;
    $phy_tot_max_marks = NULL;

    $chem_tot_right = NULL;
    $chem_tot_wrong = NULL;
    $chem_tot_left = NULL;
    $chem_tot_loss = NULL;
    $chem_tot_marks = NULL;
    $chem_tot_max_marks = NULL;

    $maths_tot_right = NULL;
    $maths_tot_wrong = NULL;
    $maths_tot_left = NULL;
    $maths_tot_loss = NULL;
    $maths_tot_marks = NULL;
    $maths_tot_max_marks = NULL;

    $tot_right = NULL;
    $tot_wrong = NULL;
    $tot_left = NULL;
    $tot_loss = NULL;
    $tot_marks = NULL;
    $tot_max_marks = NULL;

    $rank = NULL;

    $centre_name = $value;
    $roll_no = $user['userid'];
    $stream = $paper_data['stream'];
    $paper_name = strtolower($paper_data['name']);

    $phy_tot_right = round($user_spr[0]['pcorr'],1);
    $phy_tot_wrong = round($user_spr[0]['pwrong'],1);
    $phy_tot_left = $paper_data['nphy']-($phy_tot_right + $phy_tot_wrong);
    $phy_tot_loss = round($user_spr[0]['pnegmarks'],1);
    $phy_tot_marks = round($user_spr[0]['pobt'],1);
    $phy_tot_max_marks = round($paper_data['ntotphy'],1);

    $chem_tot_right = round($user_spr[0]['ccorr'],1);
    $chem_tot_wrong = round($user_spr[0]['cwrong'],1);
    $chem_tot_left = $paper_data['nchem']-($chem_tot_right + $chem_tot_wrong);
    $chem_tot_loss = round($user_spr[0]['cnegmarks'],1);
    $chem_tot_marks = round($user_spr[0]['cobt'],1);
    $chem_tot_max_marks = round($paper_data['ntotchem'],1);
    
    $rank = $user_spr[0]['rank'];

    if($stream == 'Eng'){
        $maths_tot_right = round($user_spr[0]['mcorr'],1);
        $maths_tot_wrong = round($user_spr[0]['mwrong'],1);
        $maths_tot_left = $paper_data['nmath']-($maths_tot_right + $maths_tot_wrong);
        $maths_tot_loss = round($user_spr[0]['mnegmarks'],1);
        $maths_tot_marks = round($user_spr[0]['mobt'],1);
        $maths_tot_max_marks = round($paper_data['ntotmath'],1);

        $tot_right = $phy_tot_right + $chem_tot_right + $maths_tot_right;
        $tot_wrong = $phy_tot_wrong + $chem_tot_wrong + $maths_tot_wrong;
        $tot_left = $phy_tot_left + $chem_tot_left + $maths_tot_left;
        $tot_loss = $phy_tot_loss + $chem_tot_loss + $maths_tot_loss;
        $tot_marks = $phy_tot_marks + $chem_tot_marks + $maths_tot_marks;
        $tot_max_marks = $phy_tot_max_marks + $chem_tot_max_marks + $maths_tot_max_marks;

        $csv_data[]=array($centre_name,$rank,$roll_no,
        $phy_tot_right,$phy_tot_wrong,$phy_tot_left,$phy_tot_loss,$phy_tot_marks,$phy_tot_max_marks,
        $chem_tot_right,$chem_tot_wrong,$chem_tot_left,$chem_tot_loss,$chem_tot_marks,$chem_tot_max_marks,
        $maths_tot_right,$maths_tot_wrong,$maths_tot_left,$maths_tot_loss,$maths_tot_marks,$maths_tot_max_marks,
        $tot_right,$tot_wrong,$tot_left,$tot_loss,$tot_marks,$tot_max_marks);
    }
    else if($stream == 'Med'){
        $bio_tot_right = round($user_spr[0]['bcorr'],1);
        $bio_tot_wrong = round($user_spr[0]['bwrong'],1);
        $bio_tot_left = $paper_data['nbio']-($bio_tot_right + $bio_tot_wrong);
        $bio_tot_loss = round($user_spr[0]['bnegmarks'],1);
        $bio_tot_marks = round($user_spr[0]['bobt'],1);
        $bio_tot_max_marks = round($paper_data['ntotbio'],1);

        $tot_right = $phy_tot_right + $chem_tot_right + $bio_tot_right;
        $tot_wrong = $phy_tot_wrong + $chem_tot_wrong + $bio_tot_wrong;
        $tot_left = $phy_tot_left + $chem_tot_left + $bio_tot_left;
        $tot_loss = $phy_tot_loss + $chem_tot_loss + $bio_tot_loss;
        $tot_marks = $phy_tot_marks + $chem_tot_marks + $bio_tot_marks;
        $tot_max_marks = $phy_tot_max_marks + $chem_tot_max_marks + $bio_tot_max_marks;

        if((strpos($paper_name, 'aiims') !== false )){
            $bio_tot_max_marks = 80;
            $tot_max_marks = 200;
        }

        $csv_data[]=array($centre_name,$rank,$roll_no,
        $phy_tot_right,$phy_tot_wrong,$phy_tot_left,$phy_tot_loss,$phy_tot_marks,$phy_tot_max_marks,
        $chem_tot_right,$chem_tot_wrong,$chem_tot_left,$chem_tot_loss,$chem_tot_marks,$chem_tot_max_marks,
        $bio_tot_right,$bio_tot_wrong,$bio_tot_left,$bio_tot_loss,$bio_tot_marks,$bio_tot_max_marks,
        $tot_right,$tot_wrong,$tot_left,$tot_loss,$tot_marks,$tot_max_marks);
    }
    else if($stream == 'PreFoundation'){
        $maths_tot_right = round($user_spr[0]['mcorr'],1);
        $maths_tot_wrong = round($user_spr[0]['mwrong'],1);
        $maths_tot_left = $paper_data['nmath']-($maths_tot_right + $maths_tot_wrong);
        $maths_tot_loss = round($user_spr[0]['mnegmarks'],1);
        $maths_tot_marks = round($user_spr[0]['mobt'],1);
        $maths_tot_max_marks = round($paper_data['ntotmath'],1);

        $bio_tot_right = round($user_spr[0]['bcorr'],1);
        $bio_tot_wrong = round($user_spr[0]['bwrong'],1);
        $bio_tot_left = $paper_data['nbio']-($bio_tot_right + $bio_tot_wrong);
        $bio_tot_loss = round($user_spr[0]['bnegmarks'],1);
        $bio_tot_marks = round($user_spr[0]['bobt'],1);
        $bio_tot_max_marks = round($paper_data['ntotbio'],1);

        $ma_tot_right = round($user_spr[0]['macorr'],1);
        $ma_tot_wrong = round($user_spr[0]['mawrong'],1);
        $ma_tot_left = $paper_data['nmat']-($ma_tot_right + $ma_tot_wrong);
        $ma_tot_loss = round($user_spr[0]['manegmarks'],1);
        $ma_tot_marks = round($user_spr[0]['maobt'],1);
        $ma_tot_max_marks = round($paper_data['ntotmat'],1);

        $tot_right = $phy_tot_right + $chem_tot_right + $maths_tot_right + $bio_tot_right + $ma_tot_right;
        $tot_wrong = $phy_tot_wrong + $chem_tot_wrong + $maths_tot_wrong+ $bio_tot_wrong + $ma_tot_wrong;
        $tot_left = $phy_tot_left + $chem_tot_left + $maths_tot_left +$bio_tot_left + $ma_tot_left;
        $tot_loss = $phy_tot_loss + $chem_tot_loss + $maths_tot_loss +$bio_tot_loss + $ma_tot_loss;
        $tot_marks = $phy_tot_marks + $chem_tot_marks + $maths_tot_marks +$bio_tot_marks + $ma_tot_marks;
        $tot_max_marks = $phy_tot_max_marks + $chem_tot_max_marks + $maths_tot_max_marks +$bio_tot_max_marks + $ma_tot_max_marks;

        $csv_data[]=array($centre_name,$rank,$roll_no,
        $phy_tot_right,$phy_tot_wrong,$phy_tot_left,$phy_tot_loss,$phy_tot_marks,$phy_tot_max_marks,
        $chem_tot_right,$chem_tot_wrong,$chem_tot_left,$chem_tot_loss,$chem_tot_marks,$chem_tot_max_marks,
        $maths_tot_right,$maths_tot_wrong,$maths_tot_left,$maths_tot_loss,$maths_tot_marks,$maths_tot_max_marks,
        $bio_tot_right,$bio_tot_wrong,$bio_tot_left,$bio_tot_loss,$bio_tot_marks,$bio_tot_max_marks,
        $ma_tot_right,$ma_tot_wrong,$ma_tot_left,$ma_tot_loss,$ma_tot_marks,$ma_tot_max_marks,
        $tot_right,$tot_wrong,$tot_left,$tot_loss,$tot_marks,$tot_max_marks);
    }

    foreach ($csv_data as $row)
    {
        fputcsv($file_name, $row);
    }

    
}


function get_batch_name($target,$batch,$link_id){

    $batch_name = NULL;
    $sql="SELECT batchname FROM batches WHERE batchid=$batch AND batchtargetyear=$target";
    $result=mysqli_query($link_id,$sql);
    if(mysqli_num_rows($result) > 0 ){
        while ($row=mysqli_fetch_assoc($result)){
                $batch_name= $row['batchname'];
        }
    }
    //echo "<pre>";
    //print_r($user);
    return $batch_name;
}


function send_email_centres($paper_data,$file_name,$value,$key,$branch_email,$link_id){
    include('config.php');
    //echo $branch_email;

    $batch_name = get_batch_name($target,$batch,$link_id);
    $paper_name = $paper_data['name'];
    $paper_date=substr($paper_data['startdate'],0,10);

    $branch_email = 'pandurang.aglave@raoiit.com'; 
    //$branch_email = ''; 
    //$ranklist_email = 'abhishek.pawar@raoiit.com';
    $ranklist_email = 'ranklist@raoiit.com';
    //$ranklist_email = '';
    
    $user_emails = $branch_email . ',' . $ranklist_email ;

    $branch_name = $value;   
    //$user_emails=$user['email'];
    $user_emails = ltrim($user_emails, ',');
    $user_emails = rtrim($user_emails, ',');
    $user_email_id=explode(",", $user_emails);
    $email_id=array_unique($user_email_id);

    $email_message= "Dear $value branch team members,<br/><br/>
    Plz find the attachment of $batch_name ranklist.
    This ranklist display on the notice board without name.<br/><br/>
    <b>Test Name : </b>$paper_name <br/>
    <b>Test Date : </b>$paper_date.<br/><br/>

    Total number of students appeared for this test : $total_students <br/><br/>

    <u><b>NOTE:This is a system generated mail.Please do not reply to this email ID.</b></u><br/><br/>
    
    <b>If you have any queries regarding ranklist kindly contact at <a href=mailto:ranklist@raoiit.com>ranklist@raoiit.com</a></b><br/>
    <h3><b>Or Contact on below Number : </b></h3>
    <b>Miss. Reshma Ghadigaonkar : </b>9167393331 <br/>
    <b>Mr. Ganesh Patil : </b>7506405580 <br/>
    <b>Mr. Prathmesh Lakhan  : </b>8291826189 <br/>
            
    <br><br>


    -------------<br>
    Thanks & Regards<br>
    Rao IIT Academy ";

    $from = new Email("Rao IIT Notification", "sg@raoiit.com");
    $subject = "Center Ranklist - ".$paper_name.'-'.'['.$batch_name.']';

    echo PHP_EOL;
    echo 'Sending Email to : '.$branch_name.PHP_EOL;

    $to = new Email($branch_name, $email_id[0]);       // primary email-id of student
    $content = new Content("text/html",$email_message);
    $mail = new Mail($from, $subject, $to, $content);
    echo 'Email ID - '.$user_email_id[0].PHP_EOL;

    for($i=1;$i<count($user_email_id);$i++){
        if(@$email_id[$i]==''){
            continue;
        }
        $email[$i+2] = new Email('Ranklist', $email_id[$i]);                // extract email id if more than one
        $mail->personalization[0]->addTo($email[$i+2]);
        echo 'Email ID - '.@$user_email_id[$i].PHP_EOL;
    }


    $apiKey = "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";
    $sg = new \SendGrid($apiKey);

    $attachment = new Attachment();
    $attachment->setContent(base64_encode(file_get_contents($file_name)));
    $attachment->setType("application/csv");
    $attachment->setFilename($paper_name."-ranklist.csv");
    $attachment->setDisposition("attachment");
    $attachment->setContentId("Ranklist");
    $mail->addAttachment($attachment);

    $response = $sg->client->mail()->send()->post($mail);
    $res_code=$response->statusCode();
    $status_info=implode(" ",$response->headers());
    echo $response->body().PHP_EOL;
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

$branch_email = NULL;
echo 'Initiating Script'.PHP_EOL;
$link_id=connect_analysis_db();    // Database Connection 
$active_centre=get_active_centres($link_id);         // All active centres
//$active_centre=array("4"=>"Powai (CSC)");                  //For Engineering Branches


$z = NULL;
$file_name = NULL;
foreach ($active_centre as $key => $value){

    $branch_email = get_centre_email($link_id,$key);
    echo 'Centre - '.$value.PHP_EOL;
    echo 'Fetching Users'.PHP_EOL;
    $user = get_centre_user_info($link_id,$key);   // Userinfo of current centre
    
    echo 'Fetching Paperinfo'.PHP_EOL;
    $paper_data=get_paperinfo($paperid);
    $paper_date=substr($paper_data['startdate'],0,10);
    $stream = $paper_data['stream'];
    $paper_name=$paper_data['name'];
    $max = sizeof($user);
    echo 'Total Users belonging to this centre - '.$max.PHP_EOL;
    if(!file_exists('/media/E_Drive/Branch_Ranklist/'.$paperid)){
        mkdir('/media/E_Drive/Branch_Ranklist/'.$paperid);
    }

    if($max != 0){
        if($stream == 'Eng'){
            $file_name='/media/E_Drive/Branch_Ranklist/'.$paperid.'/'.$value.'.csv';
            if(file_exists($file_name)){
                $file_name = fopen($file_name, 'a');
            }
            else{
                $file_name = fopen($file_name, 'w');
                fputcsv($file_name, array('Test Name-'.$paper_name, 'Date-'.$paper_date));
                fputcsv($file_name, array('CENTER', 'Common Rank','Roll No.', 
                    'PHY Right', 'PHY Wrong', 'PHY Left', 'PHY Loss', 'PHY Marks', 'PHY Max Mrk', 
                    'CHEM Right', 'CHEM Wrong', 'CHEM Left', 'CHEM Loss', 'CHEM Marks', 'CHEM Max Mrk', 'MATHS Right', 'MATHS Wrong', 'MATHS Left', 'MATHS Loss', 'MATHS Marks', 'MATHS Max Mrk', 'Total Right', 'Total Wrong', 'Total Left', 'Total Loss', 'Total Marks', 'Total Max Mrk'));
                
            }
        }
        else if($stream == 'Med'){
            $file_name='/media/E_Drive/Branch_Ranklist/'.$paperid.'/'.$value.'.csv';
            if(file_exists($file_name)){
                $file_name = fopen($file_name, 'a');
            }
            else{
                $file_name = fopen($file_name, 'w');
                fputcsv($file_name, array('Test Name-'.$paper_name, 'Date-'.$paper_date));
                fputcsv($file_name, array('CENTER', 'Common Rank','Roll No.', 
                    'PHY Right', 'PHY Wrong', 'PHY Left', 'PHY Loss', 'PHY Marks', 'PHY Max Mrk', 
                    'CHEM Right', 'CHEM Wrong', 'CHEM Left', 'CHEM Loss', 'CHEM Marks', 'CHEM Max Mrk', 'BIO Right', 'BIO Wrong', 'BIO Left', 'BIO Loss', 'BIO Marks', 'BIO Max Mrk', 'Total Right', 'Total Wrong', 'Total Left', 'Total Loss', 'Total Marks', 'Total Max Mrk'));
                
            }
        }
        else if($stream == 'PreFoundation'){
            $file_name='/media/E_Drive/Branch_Ranklist/'.$paperid.'/'.$value.'.csv';
            if(file_exists($file_name)){
                $file_name = fopen($file_name, 'a');
            }
            else{
                $file_name = fopen($file_name, 'w');
                fputcsv($file_name, array('Test Name-'.$paper_name, 'Date-'.$paper_date));
                fputcsv($file_name, array('CENTER', 'Common Rank','Roll No.', 
                    'PHY Right', 'PHY Wrong', 'PHY Left', 'PHY Loss', 'PHY Marks', 'PHY Max Mrk', 
                    'CHEM Right', 'CHEM Wrong', 'CHEM Left', 'CHEM Loss', 'CHEM Marks', 'CHEM Max Mrk', 'MATHS Right', 'MATHS Wrong', 'MATHS Left', 'MATHS Loss', 'MATHS Marks', 'MATHS Max Mrk', 'BIO Right', 'BIO Wrong', 'BIO Left', 'BIO Loss', 'BIO Marks', 'BIO Max Mrk', 'MA Right', 'MA Wrong', 'MA Left', 'MA Loss', 'MA Marks', 'MA Max Mrk', 'Total Right', 'Total Wrong', 'Total Left', 'Total Loss', 'Total Marks', 'Total Max Mrk'));
                
            }
        }    
    }
    
    
    $z = 0;
    for($i = 0; $i < $max;$i++){
        if($max != 0){
            $user_spr=get_user_spr($user[$i]);      // get student performance report
            if(empty($user_spr) || $user_spr=="USER DNE" || ($user_spr[0]['pobt']==0 && $user_spr[0]['cobt']==0 && $user_spr[0]['mobt']==0 && $user_spr[0]['bobt']==0)){
            $result_file='/media/E_Drive/Branch_Ranklist/'.$paperid.'/'.$value.'.csv';
            $z = $z+1;
            if($z == $max){
                unlink($result_file);
            }
            
            continue;
        }
        echo 'Adding data to excel file for roll no - '.$user[$i]['userid'].PHP_EOL;
        output_data($user[$i],$user_spr,$paper_data,$file_name,$value,$key);
        //break;
        }
        
    }
    $file_name='/media/E_Drive/Branch_Ranklist/'.$paperid.'/'.$value.'.csv';
    if($max == 0 || !file_exists($file_name)){
        continue;
    }
    else{
        send_email_centres($paper_data,$file_name,$value,$key,$branch_email,$link_id);
        break;
    }
    
}

close_analysis_db($link_id);         // close database connection
?>