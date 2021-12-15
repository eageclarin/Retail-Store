var duration = 60*10;
setInterval(updateTimer, 1000);

let nameName  = document.getElementById("helper").getAttribute("data-name");

function updateTimer() {
    if (window.location.href != "http://localhost:8080/CMSC-127/session/RetailProject/client/register.php" &&  nameName != null) {
        duration--;
        if (duration<1) {

            window.location="http://localhost/CMSC-127/session/RetailProject/env/idle.php";
            if ( window.history.replaceState ) {
                window.history.replaceState( null, null, window.location.href );
            }
        }; 
    };
    console.log(duration);
    console.log(nameName);
    
};

window.addEventListener("mousemove", resetTimer);

function resetTimer() {
     duration = 60*10;
};


