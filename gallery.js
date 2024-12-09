document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('gallery-modal');
    const closeModal = modal.querySelector('.close');
    const prevBtn = modal.querySelector('.prev');
    const nextBtn = modal.querySelector('.next');
    const slide = modal.querySelector('.slide');
    let currentImages = [];
    let currentIndex = 0;
  
    // Open modal on gallery item click
    document.querySelectorAll('.gallery-item').forEach(item => {
      if (!item.classList.contains('empty')) {
        item.addEventListener('click', () => {
          currentImages = item.getAttribute('data-images').split(',');
          currentIndex = 0;
          updateSlide();
          modal.style.display = 'flex';
        });
      }
    });
  
    // Close modal
    closeModal.addEventListener('click', () => {
      modal.style.display = 'none';
    });
  
    // Show previous image
    prevBtn.addEventListener('click', () => {
      currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
      updateSlide();
    });
  
    // Show next image
    nextBtn.addEventListener('click', () => {
      currentIndex = (currentIndex + 1) % currentImages.length;
      updateSlide();
    });
  
    // Update the displayed slide
    function updateSlide() {
      slide.src = currentImages[currentIndex];
    }
  });
  
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