/* ============================================
   LUX Landing Page - JavaScript
   ============================================ */

document.addEventListener('DOMContentLoaded', function () {
    initNavbar();
    initHeroSlider();
    initProductSlider();
    initChatWidget();
});

/* ----------------------------------------
   Navbar - Scroll Detection & Mobile Menu
   ---------------------------------------- */
function initNavbar() {
    const navbar = document.getElementById('navbar');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');

    if (!navbar) return;

    // Scroll detection for navbar background
    function handleScroll() {
        if (window.scrollY > 20) {
            navbar.classList.add('navbar-scrolled');
            navbar.classList.remove('navbar-default');
        } else {
            navbar.classList.remove('navbar-scrolled');
            navbar.classList.add('navbar-default');
        }
    }

    window.addEventListener('scroll', handleScroll);
    handleScroll(); // Initial check

    // Mobile menu toggle
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function () {
            mobileMenu.classList.add('open');
            mobileMenu.classList.remove('closed');
            document.body.style.overflow = 'hidden';
        });
    }

    if (mobileMenuClose && mobileMenu) {
        mobileMenuClose.addEventListener('click', function () {
            mobileMenu.classList.remove('open');
            mobileMenu.classList.add('closed');
            document.body.style.overflow = '';
        });
    }
}

/* ----------------------------------------
   Hero Slider - Auto-slide & Navigation
   ---------------------------------------- */
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');

    if (slides.length === 0) return;

    let currentIndex = 0;
    const totalSlides = slides.length;
    const autoSlideInterval = 8000; // 8 seconds
    let intervalId;

    function showSlide(index) {
        // Update slides
        slides.forEach((slide, i) => {
            const content = slide.querySelector('.hero-content');
            if (i === index) {
                slide.classList.add('active');
                slide.classList.remove('inactive');
                if (content) {
                    content.classList.add('active');
                    content.classList.remove('inactive');
                }
            } else {
                slide.classList.remove('active');
                slide.classList.add('inactive');
                if (content) {
                    content.classList.remove('active');
                    content.classList.add('inactive');
                }
            }
        });

        // Update dots
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('active');
                dot.classList.remove('inactive');
            } else {
                dot.classList.remove('active');
                dot.classList.add('inactive');
            }
        });

        currentIndex = index;
    }

    function nextSlide() {
        const nextIndex = (currentIndex + 1) % totalSlides;
        showSlide(nextIndex);
    }

    function startAutoSlide() {
        intervalId = setInterval(nextSlide, autoSlideInterval);
    }

    function stopAutoSlide() {
        if (intervalId) {
            clearInterval(intervalId);
        }
    }

    // Dot click handlers
    dots.forEach((dot, index) => {
        dot.addEventListener('click', function () {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });

    // Initialize
    showSlide(0);
    startAutoSlide();
}

/* ----------------------------------------
   Product Slider - Horizontal Scroll
   ---------------------------------------- */
function initProductSlider() {
    const scrollContainer = document.getElementById('product-scroll');
    const prevBtn = document.getElementById('product-prev');
    const nextBtn = document.getElementById('product-next');

    if (!scrollContainer) return;

    function updateButtons() {
        const { scrollLeft, scrollWidth, clientWidth } = scrollContainer;

        if (prevBtn) {
            prevBtn.disabled = scrollLeft <= 10;
        }
        if (nextBtn) {
            nextBtn.disabled = scrollLeft >= scrollWidth - clientWidth - 10;
        }
    }

    function scroll(direction) {
        const scrollAmount = scrollContainer.clientWidth;
        scrollContainer.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', () => scroll('left'));
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', () => scroll('right'));
    }

    scrollContainer.addEventListener('scroll', updateButtons);
    updateButtons(); // Initial check
}

/* ----------------------------------------
   Chat Widget - Open/Close
   ---------------------------------------- */
function initChatWidget() {
    const chatButton = document.getElementById('chat-button');
    const chatWidget = document.getElementById('chat-widget');
    const chatClose = document.getElementById('chat-close');

    if (!chatButton || !chatWidget) return;

    function openChat() {
        chatWidget.classList.add('open');
        chatWidget.classList.remove('closed');
        chatButton.classList.add('hidden');
        chatButton.classList.remove('visible');
    }

    function closeChat() {
        chatWidget.classList.remove('open');
        chatWidget.classList.add('closed');
        chatButton.classList.remove('hidden');
        chatButton.classList.add('visible');
    }

    chatButton.addEventListener('click', openChat);

    if (chatClose) {
        chatClose.addEventListener('click', closeChat);
    }
}
