<?php

include('config.php');

    $link_id = new mysqli($host,$username,$pass,$db);
    if (!$link_id) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    
    $sql="SELECT id,name FROM ttbatches WHERE  status=1 AND targetyear=2019 AND (batch=0)";
    $result=mysqli_query($link_id,$sql);
    if(mysqli_num_rows($result) > 0){
        while ($row=mysqli_fetch_assoc($result)){
            $data[$row['id']] = $row['name'];
        }
    }
	echo "<pre>";
    print_r($data);
    return $data;
    
/*
    $sql="SELECT userid,username FROM userinfo WHERE  status=1 AND targetyear=2019 AND centre=45 AND (batch=0)";
    $result=mysqli_query($link_id,$sql);
    if(mysqli_num_rows($result) > 0){
        while ($row=mysqli_fetch_assoc($result)){
            $data[$row['userid']] = $row['username'];
        }
    }
    echo "<pre>";
    print_r($data);
    return $data;  
*/



?>