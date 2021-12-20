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

	} else {
		echo "ERROR: Could not be able to execute $sql." . mysqli_error($conn);
	}

?>

<script>
    var duration = 60*10;
    setInterval(updateTimer, 1000);
    let aria = <?php echo isset($_SESSION['username']);?> + "";
    let result = window.location.origin; 
    function updateTimer() {
        if (window.location.pathname != "/CMSC-127/session/RetailProject/client/register.php" && aria !='0') {
            duration--;
        if (duration<1) {
            window.location=result.concat("/CMSC-127/session/RetailProject/env/idle.php");
        } 
        }

    }

    window.addEventListener("mousemove", resetTimer);

    function resetTimer() {
        duration =60*10;
    }
</script>