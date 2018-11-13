<?php
namespace SendGrid;
include('config.php');
include('db_connect.php');
include('active_centres.php');
error_reporting(0);

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

function get_paperinfo2($paperid2){
    include('config.php');
    
    $paper_info = $paper_info_cURL."pid=$paperid2";   // cURL for getting test paper info
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

function get_user_spr2($user){
    include('config.php');

    $userid = $user['userid'];
    $loginUrl = $spr_cURL."username=$userid&pid=$paperid2";
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
            $bio_tot_left = 80 -($bio_tot_right + $bio_tot_wrong);
            $tot_left = $phy_tot_left + $chem_tot_left + $bio_tot_left;
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

function output_data2($user,$user_spr,$user_spr2,$paper_data,$paper_data2,$file_name,$value,$key){
    include('config.php');
    //echo "<pre>";
    //print_r($user_spr);

    //print_r($user_spr2);
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

    $paper_name2 = NULL;
    $stream2 = NULL;
    $result_data2 = array();
    $centre_name2 = NULL;
    $roll_no2 = NULL;

    $phy_tot_right2 = NULL;
    $phy_tot_wrong2 = NULL;
    $phy_tot_left2 = NULL;
    $phy_tot_loss2 = NULL;
    $phy_tot_marks2 = NULL;
    $phy_tot_max_marks2 = NULL;

    $chem_tot_right2 = NULL;
    $chem_tot_wrong2 = NULL;
    $chem_tot_left2 = NULL;
    $chem_tot_loss2 = NULL;
    $chem_tot_marks2 = NULL;
    $chem_tot_max_marks2 = NULL;

    $maths_tot_right2 = NULL;
    $maths_tot_wrong2 = NULL;
    $maths_tot_left2 = NULL;
    $maths_tot_loss2 = NULL;
    $maths_tot_marks2 = NULL;
    $maths_tot_max_marks2 = NULL;

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
    $phy_tot_left = $n_phy1 -($phy_tot_right + $phy_tot_wrong);
    $phy_tot_loss = round($user_spr[0]['pnegmarks'],1);
    $phy_tot_marks = round($user_spr[0]['pobt'],1);
    $phy_tot_max_marks = round($paper_data['ntotphy'],1);

    $chem_tot_right = round($user_spr[0]['ccorr'],1);
    $chem_tot_wrong = round($user_spr[0]['cwrong'],1);
    $chem_tot_left = $n_chem1 -($chem_tot_right + $chem_tot_wrong);
    $chem_tot_loss = round($user_spr[0]['cnegmarks'],1);
    $chem_tot_marks = round($user_spr[0]['cobt'],1);
    $chem_tot_max_marks = round($paper_data['ntotchem'],1);

    //Advanced Paper-2

    $stream2 = $paper_data2['stream'];
    $paper_name2 = strtolower($paper_data2['name']);

    $phy_tot_right2 = round($user_spr2[0]['pcorr'],1);
    $phy_tot_wrong2 = round($user_spr2[0]['pwrong'],1);
    $phy_tot_left2 = $n_phy2 -($phy_tot_right2 + $phy_tot_wrong2);
    $phy_tot_loss2 = round($user_spr2[0]['pnegmarks'],1);
    $phy_tot_marks2 = round($user_spr2[0]['pobt'],1);
    $phy_tot_max_marks2 = round($paper_data2['ntotphy'],1);

    $chem_tot_right2 = round($user_spr2[0]['ccorr'],1);
    $chem_tot_wrong2 = round($user_spr2[0]['cwrong'],1);
    $chem_tot_left2 = $n_chem2 -($chem_tot_right2 + $chem_tot_wrong2);
    $chem_tot_loss2 = round($user_spr2[0]['cnegmarks'],1);
    $chem_tot_marks2 = round($user_spr2[0]['cobt'],1);
    $chem_tot_max_marks2 = round($paper_data2['ntotchem'],1);
    
    $rank = $user_spr[0]['rank'];

    $rank2 = $user_spr2[0]['rank'];

    if(empty($user_spr)){
        $rank = $rank2;
    }

    if($stream == 'Eng' && $stream2 == 'Eng'){
        $maths_tot_right = round($user_spr[0]['mcorr'],1);
        $maths_tot_wrong = round($user_spr[0]['mwrong'],1);
        $maths_tot_left = $n_maths1 -($maths_tot_right + $maths_tot_wrong);
        $maths_tot_loss = round($user_spr[0]['mnegmarks'],1);
        $maths_tot_marks = round($user_spr[0]['mobt'],1);
        $maths_tot_max_marks = round($paper_data['ntotmath'],1);

        //Advanced Paper-2
        $maths_tot_right2 = round($user_spr2[0]['mcorr'],1);
        $maths_tot_wrong2 = round($user_spr2[0]['mwrong'],1);
        $maths_tot_left2 = $n_maths2 -($maths_tot_right2 + $maths_tot_wrong2);
        $maths_tot_loss2 = round($user_spr2[0]['mnegmarks'],1);
        $maths_tot_marks2 = round($user_spr2[0]['mobt'],1);
        $maths_tot_max_marks2 = round($paper_data2['ntotmath'],1);

        $right1 = $phy_tot_right + $chem_tot_right + $maths_tot_right;
        $wrong1 = $phy_tot_wrong + $chem_tot_wrong + $maths_tot_wrong;
        $left1 = $phy_tot_left + $chem_tot_left + $maths_tot_left;
        $loss1 = $phy_tot_loss + $chem_tot_loss + $maths_tot_loss;
        $marks1 = $phy_tot_marks + $chem_tot_marks + $maths_tot_marks;
        $max_marks1 = round($paper_data['maximummarks'],1);

        $right2 = $phy_tot_right2 + $chem_tot_right2 + $maths_tot_right2;
        $wrong2 = $phy_tot_wrong2 + $chem_tot_wrong2 + $maths_tot_wrong2;
        $left2 = $phy_tot_left2 + $chem_tot_left2 + $maths_tot_left2;
        $loss2 = $phy_tot_loss2 + $chem_tot_loss2 + $maths_tot_loss2;
        $marks2 = $phy_tot_marks2 + $chem_tot_marks2 + $maths_tot_marks2;
        $max_marks2 = round($paper_data2['maximummarks'],1);

        $phy_tot = $phy_tot_marks + $phy_tot_marks2;
        $chem_tot = $chem_tot_marks + $chem_tot_marks2;
        $maths_tot = $maths_tot_marks + $maths_tot_marks2;


        $tot_right = $phy_tot_right + $chem_tot_right + $maths_tot_right + $phy_tot_right2 + $chem_tot_right2 + $maths_tot_right2;
        $tot_wrong = $phy_tot_wrong + $chem_tot_wrong + $maths_tot_wrong + $phy_tot_wrong2 + $chem_tot_wrong2 + $maths_tot_wrong2;
        $tot_left = $phy_tot_left + $chem_tot_left + $maths_tot_left + $phy_tot_left2 + $chem_tot_left2 + $maths_tot_left2;
        $tot_loss = $phy_tot_loss + $chem_tot_loss + $maths_tot_loss + $phy_tot_loss2 + $chem_tot_loss2 + $maths_tot_loss2;
        $tot_marks = $phy_tot_marks + $chem_tot_marks + $maths_tot_marks + $phy_tot_marks2 + $chem_tot_marks2 + $maths_tot_marks2;
        $tot_max_marks = $phy_tot_max_marks + $chem_tot_max_marks + $maths_tot_max_marks + $phy_tot_max_marks2 + $chem_tot_max_marks2 + $maths_tot_max_marks2;
        if(empty($user_spr)){
            $left1 = 0;
            $tot_left =  $left2;
        }
        else
        if(empty($user_spr2)){
            $left2 = 0;
            $tot_left =  $left1;
        }

        $csv_data[]=array($centre_name,$rank,$roll_no,
        $phy_tot_marks,$chem_tot_marks,$maths_tot_marks,$right1,$wrong1,$left1,$loss1,$marks1,$max_marks1,$phy_tot_marks2,$chem_tot_marks2,$maths_tot_marks2,$right2,$wrong2,$left2,$loss2,$marks2,$max_marks2,$phy_tot,$chem_tot,$maths_tot,$tot_right,$tot_wrong,$tot_left,$tot_loss,$tot_marks,$tot_max_marks);
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

    //$branch_email = 'pandurang.aglave@raoiit.com'; 
    //$branch_email = ''; 
    
    $user_emails = $branch_email;

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
    
    <b>If you have any queries regarding ranklist kindly contact at <a href=mailto:reshma.ghadigaonkar@raoiit.com>reshma.ghadigaonkar@raoiit.com</a></b><br/>
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


function send_email_centres2($paper_data,$paper_data2,$file_name,$value,$key,$branch_email,$link_id){
    include('config.php');
    //echo $branch_email;

    $batch_name = get_batch_name($target,$batch,$link_id);
    $paper_name = $paper_data['name'];
    $paper_name2 = $paper_data2['name'];
    $paper_date=substr($paper_data['startdate'],0,10);

    //$branch_email = 'pandurang.aglave@raoiit.com'; 
    //$branch_email = ''; 
    $user_emails = $branch_email;
    //$user_emails = $branch_email ;

    $branch_name = $value;   
    //$user_emails=$user['email'];
    $user_emails = ltrim($user_emails, ',');
    $user_emails = rtrim($user_emails, ',');
    $user_email_id=explode(",", $user_emails);
    $email_id=array_unique($user_email_id);

    $email_message= "Dear $value branch team members,<br/><br/>
    Plz find the attachment of $batch_name ranklist.
    This ranklist display on the notice board without name.<br/><br/>
    <b>Test Name 1: </b>$paper_name <br/>
    <b>Test Name 2: </b>$paper_name2 <br/>
    <b>Test Date : </b>$paper_date.<br/><br/>

    Total number of students appeared for this test : $total_students <br/><br/>

    <u><b>NOTE:This is a system generated mail.Please do not reply to this email ID.</b></u><br/><br/>
    
    <b>If you have any queries regarding ranklist kindly contact at <a href=mailto:reshma.ghadigaonkar@raoiit.com>reshma.ghadigaonkar@raoiit.com</a></b><br/>
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

function send_email_ranklist($paperid,$paperid2,$paper_data,$link_id){
    include('config.php');
    $batch_name = get_batch_name($target,$batch,$link_id);
    $paper_name = $paper_data['name'];
    $paper_name2 = $paper_data2['name'];
    $paper_date=substr($paper_data['startdate'],0,10);
    $ranklist_email = 'prathamesh.lakhan@raoiit.com,reshma.ghadigaonkar@raoiit.com';
    //$ranklist_email = 'pandurang.aglave@raoiit.com';
    //$ranklist_email = '';

    $user_emails = $ranklist_email;

    $user_emails = ltrim($user_emails, ',');
    $user_emails = rtrim($user_emails, ',');
    $user_email_id=explode(",", $user_emails);
    $email_id=array_unique($user_email_id);

    if($paperid2 == '0'){
        $email_message= "Dear ranklist team,<br/><br/>
        Plz find the attachment of $batch_name ranklist.
        This ranklist is already sent to respective centres.<br/><br/>
        <b>Test Name 1: </b>$paper_name <br/>
        <b>Test Date : </b>$paper_date.<br/><br/>

        Total number of students appeared for this test : $total_students <br/><br/>

        <b>Please verify the same & reply me if any discrepancy present in data sent to centres.</b>
        
        <br><br>


        -------------<br>
        Thanks & Regards<br>
        Pandurang Aglave <br>
        Software Developer <br>
        8879986939 <br>
        Mumbai Head Office, Rao Edusolutions Pvt Ltd<br>
        Employee Code: 140083 ";
    }
    else{
        $email_message= "Dear ranklist team,<br/><br/>
        Plz find the attachment of $batch_name ranklist.
        This ranklist is already sent to respective centres.<br/><br/>
        <b>Test Name 1: </b>$paper_name <br/>
        <b>Test Name 2: </b>$paper_name2 <br/>
        <b>Test Date : </b>$paper_date.<br/><br/>

        Total number of students appeared for this test : $total_students <br/><br/>

        <b>Please verify the same & reply me if any discrepancy present in data sent to centres.</b>
        
        <br><br>


        -------------<br>
        Thanks & Regards<br>
        Pandurang Aglave <br>
        Software Developer <br>
        8879986939 <br>
        Mumbai Head Office, Rao Edusolutions Pvt Ltd<br>
        Employee Code: 140083 ";
    }

    

    $from = new Email("Rao IIT Notification", "sg@raoiit.com");
    $subject = $paper_name.'-'.'['.$paper_date.']';

    echo PHP_EOL;
    echo 'Sending Email to ranklist team: '.$ranklist_email.PHP_EOL;

    $to = new Email('Ranklist', $email_id[0]);       // primary email-id of student
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

    if($paperid2 == '0'){
        $dir = '/media/E_Drive/Branch_Ranklist/'.$paperid.'/';
    }
    else{
        $dir = '/media/E_Drive/Branch_Ranklist/'.$paperid.'-'.$paperid2.'/';
    }
    // Open a directory, and read its contents
    if (is_dir($dir)){
      if ($dh = opendir($dir)){
        while (($file = readdir($dh)) !== false){
            if( !is_dir($file) ){
                $attachment = new Attachment();
                $attachment->setContent(base64_encode(file_get_contents($dir.$file)));
                $attachment->setType("application/csv");
                $attachment->setFilename($file);
                $attachment->setDisposition("attachment");
                $attachment->setContentId("Ranklist");
                $mail->addAttachment($attachment);
            }
        }
        closedir($dh);
      }
    }
    
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
//$active_centre=array("52"=>"Pune (FC Road)");                  //For Engineering Branches


$z = NULL;
$file_name = NULL;
if($paperid2 == 0){
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
                        'Phy R', 'Phy W', 'Phy L', 'Phy Loss', 'Phy Marks', 'Phy Max Mrk', 
                        'Chem R', 'Chem W', 'Chem L', 'Chem Loss', 'Chem Marks', 'Chem Max Mrk', 'Maths R', 'Maths W', 'Maths L', 'Maths Loss', 'Maths Marks', 'Maths Max Mrk', 'Total R', 'Total W', 'Total L', 'Total Loss', 'Total Marks', 'Total Max Mrk'));
                    
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
                        'Phy R', 'Phy W', 'Phy L', 'Phy Loss', 'Phy Marks', 'Phy Max Mrk', 
                        'Chem R', 'Chem W', 'Chem L', 'Chem Loss', 'Chem Marks', 'Chem Max Mrk', 'Bio R', 'Bio W', 'Bio L', 'Bio Loss', 'Bio Marks', 'Bio Max Mrk', 'Total R', 'Total W', 'Total L', 'Total Loss', 'Total Marks', 'Total Max Mrk'));
                    
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
                        'Phy R', 'Phy W', 'Phy L', 'Phy Loss', 'Phy Marks', 'Phy Max Mrk', 
                        'Chem R', 'Chem W', 'Chem L', 'Chem Loss', 'Chem Marks', 'Chem Max Mrk', 'Maths R', 'Maths W', 'Maths L', 'Maths Loss', 'Maths Marks', 'Maths Max Mrk', 'Bio R', 'Bio W', 'Bio L', 'Bio Loss', 'Bio Marks', 'Bio Max Mrk', 'MA R', 'MA W', 'MA L', 'MA Loss', 'MA Marks', 'MA Max Mrk', 'Total R', 'Total W', 'Total L', 'Total Loss', 'Total Marks', 'Total Max Mrk'));
                    
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
            //break;
        }
        
    } 
}
else{

    foreach ($active_centre as $key => $value){

        $branch_email = get_centre_email($link_id,$key);
        echo 'Centre - '.$value.PHP_EOL;
        echo 'Fetching Users'.PHP_EOL;
        $user = get_centre_user_info($link_id,$key);   // Userinfo of current centre
        
        echo 'Fetching Paperinfo'.PHP_EOL;
        $paper_data = get_paperinfo($paperid);
        $paper_data2 = get_paperinfo2($paperid2);
        $paper_date = substr($paper_data['startdate'],0,10);
        $stream = $paper_data['stream'];
        $stream2 = $paper_data2['stream'];
        $paper_name = $paper_data['name'];
        $paper_name2 = $paper_data2['name'];
        $max = sizeof($user);
        echo 'Total Users belonging to this centre - '.$max.PHP_EOL;
        if(!file_exists('/media/E_Drive/Branch_Ranklist/'.$paperid.'-'.$paperid2)){
            mkdir('/media/E_Drive/Branch_Ranklist/'.$paperid.'-'.$paperid2);
        }

        if($max != 0){
            if($stream == 'Eng' && $stream2 == 'Eng'){
                $file_name='/media/E_Drive/Branch_Ranklist/'.$paperid.'-'.$paperid2.'/'.$value.'.csv';
                if(file_exists($file_name)){
                    $file_name = fopen($file_name, 'a');
                }
                else{
                    $file_name = fopen($file_name, 'w');
                    fputcsv($file_name, array('Test Name-'.$paper_name, 'Date-'.$paper_date));
                    fputcsv($file_name, array('Test Name-'.$paper_name2, 'Date-'.$paper_date));
                    fputcsv($file_name, array('CENTER', 'Common Rank','Roll No.', 
                        'Phy 1', 'Chem 1', 'Maths 1', 'R 1', 'W 1', 'L 1', 
                        'loss 1', 'Marks s1', 'Max Mark 1','Phy 2', 'Chem 2', 'Maths 2', 'R 2', 'W 2', 'L 2', 
                        'loss 2', 'Marks s2', 'Max Mark 2', 'T Phy', 'T CheM', 'T Maths', 'TR', 'TW', 'TL', 
                        'T loss', 'T Marks s1', 'T Max MarkS'));
                    
                }
            }
        }
        
        
        $z = 0;
        for($i = 0; $i < $max;$i++){
            if($max != 0){
                $user_spr = get_user_spr($user[$i]);      // get student performance report PAPER-1
                $user_spr2 = get_user_spr2($user[$i]);      // get student performance report PAPER-2
                if((empty($user_spr) && empty($user_spr2)) || $user_spr=="USER DNE" ){
                $result_file='/media/E_Drive/Branch_Ranklist/'.$paperid.'-'.$paperid2.'/'.$value.'.csv';
                $z = $z+1;
                if($z == $max){
                    unlink($result_file);
                }
                
                continue;
            }
            echo 'Adding data to excel file for roll no - '.$user[$i]['userid'].PHP_EOL;
            output_data2($user[$i],$user_spr,$user_spr2,$paper_data,$paper_data2,$file_name,$value,$key);
            //break;
            }
            
        }
        $file_name='/media/E_Drive/Branch_Ranklist/'.$paperid.'-'.$paperid2.'/'.$value.'.csv';
        if($max == 0 || !file_exists($file_name)){
            continue;
        }
        else{
            send_email_centres2($paper_data,$paper_data2,$file_name,$value,$key,$branch_email,$link_id);
            //break;
        }
        
    }
}

send_email_ranklist($paperid,$paperid2,$paper_data,$link_id);

close_analysis_db($link_id);         // close database connection

echo 'Closing Connection'.PHP_EOL;
?>