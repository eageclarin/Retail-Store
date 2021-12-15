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

        /*create tables <-- ERROR NOT WORKING
        $query = '';
        $sqlDB = file('db.sql');
        foreach ($sqlDB as $line)	{
            $startWith = substr(trim($line), 0 ,2);
            $endWith = substr(trim($line), -1 ,1);
            
            if (empty($line) || $startWith == '--') {
                continue;
            }
                
            $query = $query . $line;
            if ($endWith == ';') {
                mysqli_query($conn,$query);
                $query= '';
            } else {
                die("ERROR: Could not connect. " . mysqli_connect_error());
            }
        } */
	} else {
		echo "ERROR: Could not be able to execute $sql." . mysqli_error($conn);
	}

    /**if(time() - $_SESSION['timestamp'] > 900) { //subtract new timestamp from the old one
        echo"<script>alert('15 Minutes over!');</script>";
        unset($_SESSION['username'], $_SESSION['timestamp']);
        //$_SESSION['logged_in'] = false;
        header("Location: ../main.php"); //redirect to index.php
        exit;
    } else {
        $_SESSION['timestamp'] = time(); //set new timestamp
    }**/

	
?>

<script>
    var duration = 60*10;
    setInterval(updateTimer, 1000);
    function updateTimer() {
        if (window.location.href != "http://localhost:8080/CMSC-127/session/RetailProject/client/register.php" && <?php echo isset($_SESSION['username']);?>) {
            duration--;
        if (duration<1) {
            sessionStorage.removeItem('username');
            sessionStorage.clear();
            window.location="login.php";
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        } 
        }
        
    }

    window.addEventListener("mousemove", resetTimer);

    function resetTimer() {
        duration =60*10;
    }
</script>