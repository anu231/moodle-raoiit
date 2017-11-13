<?php

	$host = 'localhost';
	$username ='moodle';
	$pass = 'moodle';
	$db = 'moodle';

	$link = new mysqli($host,$username,$pass,$db);
    if (!$link) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    $barcode=NULL;
	$student_username=$_GET['student_username'];
	$student_password=$_GET['student_password'];
    $barcode=$_GET['code'];
    if ($student_username=='' && $student_password=='')
	{
		echo "Please insert username and password";
	}
	else {

	
    $sql = "INSERT INTO mdl_barcode (student_username,student_password,barcode)VALUES ('$student_username','$student_password','$barcode')";

		if ($link->query($sql) === TRUE) {
			echo "Data submitted successfully";
		} 
	
		else{
			echo "1";
		}

}
	$link->close();

?>