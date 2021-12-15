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
    function updateTimer() {
        if (window.location.href != "http://localhost:8080/CMSC-127/session/RetailProject/client/register.php" && aria !='0') {
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
    console.log(duration);
    console.log(aria);
    }

    window.addEventListener("mousemove", resetTimer);

    function resetTimer() {
        duration =60*10;
    }
</script>