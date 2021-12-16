var duration = 60*10;
setInterval(updateTimer, 1000);

let nameName  = document.getElementById("helper").getAttribute("data-name");

function updateTimer() {
    if (  nameName != null) {
        duration--;
        if (duration<1) {
            window.location="./idle.php";
        }; 
    };
    console.log(duration);
    console.log(nameName);
    
};

window.addEventListener("mousemove", resetTimer);

function resetTimer() {
     duration = 60*10;
};


