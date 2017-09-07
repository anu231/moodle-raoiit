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
?>