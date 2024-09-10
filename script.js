function openNav() {
    document.getElementById("sidebar").style.width = "250px";
    document.getElementById("main").style.marginLeft = "250px";
}

function closeNav() {
    document.getElementById("sidebar").style.width = "0";
    document.getElementById("main").style.marginLeft= "0";
}

window.onclick = function(event) {
    if (!event.target.matches('.openbtn')) {
        var sidebar = document.getElementById("sidebar");
        if (sidebar.style.width === "250px") {
            closeNav();
        }
    }
}
