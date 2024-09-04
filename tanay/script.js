function toggleMenu() {
    const sideMenu = document.getElementById("sideMenu");
    const hamburger = document.querySelector(".hamburger");
    sideMenu.classList.toggle("active");
    if (sideMenu.classList.contains("active")) {
        hamburger.classList.add("slide-out");
    } 
    else {
        hamburger.classList.remove("slide-out");
    }
}
