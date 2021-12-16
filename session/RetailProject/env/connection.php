

<?php
    session_start();
    $servername = "localhost";
    $username = "root";

    //create conection
    $conn = new mysqli($servername, $username, "");

    //check connection
    if($conn -> connect_errno){
		die("ERROR: Could not connect. " . mysqli_connect_error());
		exit();
	}

    //create database if not exists
	$db = "CMSC127RetailProject";
	$sql = "CREATE DATABASE IF NOT EXISTS $db";
	if (mysqli_query($conn, $sql)){
		mysqli_select_db($conn, $db); //connect to database after database created 
        //if success call connection.php

	} else {
		echo "ERROR: Could not be able to execute $sql." . mysqli_error($conn);
	}


    
  
?>
