// ===== Carousel Functionality =====

let currentSlide = 0;
let slideInterval;

function initCarousel() {
    const slides = document.querySelectorAll('.carousel-slide');
    if (slides.length === 0) return;
    
    updateCarousel();
    
    // Auto-advance every 5 seconds
    slideInterval = setInterval(() => {
        moveCarousel(1);
    }, 5000);
    
    // Pause on hover
    const carousel = document.querySelector('.carousel-container');
    if (carousel) {
        carousel.addEventListener('mouseenter', () => clearInterval(slideInterval));
        carousel.addEventListener('mouseleave', () => {
            slideInterval = setInterval(() => moveCarousel(1), 5000);
        });
    }
}

function moveCarousel(direction) {
    const slides = document.querySelectorAll('.carousel-slide');
    const totalSlides = slides.length;
    
    currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
    updateCarousel();
}

function goToSlide(index) {
    const slides = document.querySelectorAll('.carousel-slide');
    if (index >= 0 && index < slides.length) {
        currentSlide = index;
        updateCarousel();
    }
}

function updateCarousel() {
    const wrapper = document.querySelector('.carousel-wrapper');
    const slides = document.querySelectorAll('.carousel-slide');
    const dots = document.querySelectorAll('.dot');
    
    if (!wrapper || slides.length === 0) return;
    
    wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
    
    dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentSlide);
    });
}

// Initialize carousel
document.addEventListener('DOMContentLoaded', initCarousel);

// Make functions available globally
window.moveCarousel = moveCarousel;
window.goToSlide = goToSlide;