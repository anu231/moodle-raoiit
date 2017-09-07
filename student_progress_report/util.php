<?php

/*
    function for database connection
    arguments - NULL
    return - $link (used for mysqli_query(connection,query))  
    summary - establish connection to the database
*/

function connect_analysis_db(){

    $host = '192.168.1.161';
    $user = 'analysis';
    $pass = 'analysis';
    $db = 'analysis';
    
    $link = new mysqli($host,$user,$pass,$db);
    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    return $link;
}



/*
    function to get all active batches
    arguments - $link_id(from connection function)
    return - $data (id & name of all active batches)  
    summary - Get all the active batches whose status=1 & returns associative array with branch_id & branch_name
*/
function get_active_batches($link_id){
    $sql="SELECT id,name FROM ttbatches WHERE  status=1 AND targetyear=2018 AND (batch=0)";
    $result=mysqli_query($link_id,$sql);
    if(mysqli_num_rows($result) > 0){
        while ($row=mysqli_fetch_assoc($result)){
            $data[$row['id']] = $row['name'];
        }
    }
    return $data;        
}


?>