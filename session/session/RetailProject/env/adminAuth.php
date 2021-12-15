<?php 
if (!isset($_SESSION['admin'])) {
    header("Location: http://localhost/CMSC_P3/session/RetailProject/login.php"); 
}


if (isset($_POST['logout'])) {
    session_destroy();
    unset($_SESSION);
    
    header('location: http://localhost/CMSC_P3/session/RetailProject/main.php');
}
?>
<script>
    var duration = 1000;
    setInterval(updateTimer, 1000);
    var bool = "<?php echo $_SESSION['admin_User'];?>";
    function updateTimer() {
        if (window.location.href != "http://localhost:8080/CMSC-127/session/RetailProject/client/register.php" &&  bool != null ) {
            duration-=100;
        if (duration<1) {
            sessionStorage.removeItem('username');
            sessionStorage.clear();
            
            window.location="http://localhost/CMSC_P3/session/RetailProject/env/idle.php";
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        } 
        }
        console.log(duration);
        
    }

    window.addEventListener("mousemove", resetTimer);

    function resetTimer() {
        duration =1000;
    }
</script>

<!-- <script>
    var duration = 1000;
    setInterval(updateTimer, 1000);
    var bool = "<?php echo $_SESSION['admin_User'];?>";
    function updateTimer() {
        if (window.location.href != "http://localhost:8080/CMSC-127/session/RetailProject/client/register.php" && bool != null ) {
            duration-=100;
        if (duration<1) {
            sessionStorage.removeItem(bool);
            sessionStorage.clear();

            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        } 
        }
        
    }

    window.addEventListener("mousemove", resetTimer);

    function resetTimer() {
        duration =1000;
    }
</script> -->