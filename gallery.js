
let currentSlide = 0;

function openModal(index) {
    const modal = document.getElementById('myModal');
    const modalImg = document.getElementById('img01');
    const modalTitle = document.getElementById('image-title');
    const modalDesc = document.getElementById('image-desc');
    
    // Get the clicked image and its data attributes
    const galleryItems = document.getElementsByClassName('gallery-item');
    const clickedItem = galleryItems[index];
    const imgSrc = clickedItem.querySelector('img').src;
    const title = clickedItem.getAttribute('data-title');
    const description = clickedItem.getAttribute('data-description');
    
    // Set the modal content
    modal.style.display = 'block';
    modalImg.src = imgSrc;
    modalTitle.innerHTML = title;
    modalDesc.innerHTML = description;
    
    currentSlide = index;  // Keep track of current image for prev/next functionality
}

function closeModal() {
    const modal = document.getElementById('myModal');
    modal.style.display = 'none';
}

function plusSlides(n) {
    const galleryItems = document.getElementsByClassName('gallery-item');
    currentSlide += n;

    // Ensure wrapping around the gallery
    if (currentSlide < 0) {
        currentSlide = galleryItems.length - 1;
    } else if (currentSlide >= galleryItems.length) {
        currentSlide = 0;
    }

    openModal(currentSlide);
}
