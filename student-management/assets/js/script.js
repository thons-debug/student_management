// Smooth page animation
document.addEventListener("DOMContentLoaded", function() {
    document.body.style.opacity = "1";
});

// Auto fade messages
setTimeout(function() {
    let alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        alert.style.transition = "opacity 0.5s ease";
        alert.style.opacity = "0";
    });
}, 3000);

// Live Clock (optional add in header)
function updateClock() {
    let now = new Date();
    let time = now.toLocaleTimeString();
    let clock = document.getElementById("liveClock");
    if(clock) clock.innerText = time;
}

setInterval(updateClock, 1000);