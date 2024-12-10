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


document.addEventListener('scroll', function() {
    const hiddenDiv = document.querySelector('.about');
    const rect = hiddenDiv.getBoundingClientRect();

    // Check if the element is in the viewport
    if (rect.top < 500 && rect.bottom > 0) {
      hiddenDiv.classList.add('visible');
    }
  });