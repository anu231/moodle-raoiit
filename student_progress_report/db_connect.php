<?php
/*
    function for database connection
    arguments - NULL
    return - $link (used for mysqli_query(connection,query))  
    summary - establish connection to the database
*/

function connect_analysis_db(){
    include('config.php');

    $link = new mysqli($host,$username,$pass,$db);
    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    return $link;
}

function connect_admission_db(){
    include('config.php');

    $adm_link = new mysqli($adm_host,$adm_username,$adm_pass,$adm_db);
    if (!$adm_link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    return $adm_link;
}
?>