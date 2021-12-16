var duration = 60*10;
setInterval(updateTimer, 1000);
let result = window.location.origin; 
let nameName  = document.getElementById("helper").getAttribute("data-name");

function updateTimer() {
    if (nameName != null) {
        duration--;
        if (duration<1) {
             window.location=result.concat("/CMSC-127/session/RetailProject/env/idle.php");
        }; 
    };

    
};

window.addEventListener("mousemove", resetTimer);

function resetTimer() {
     duration = 60*10;
};