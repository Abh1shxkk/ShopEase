/* ============================================
   LUX Landing Page - Optimized JavaScript
   Performance-focused with reduced animations
   ============================================ */

document.addEventListener('DOMContentLoaded', function () {
    initNavbar();
    initHeroSlider();
    initProductSlider();
    initChatWidget();
    initScrollReveal();
    initMagneticButtons();
    initSmoothCounters();
    initRippleEffect();
});

/* ----------------------------------------
   Navbar - Sticky with Scroll Detection
   ---------------------------------------- */
function initNavbar() {
    const navbar = document.getElementById('navbar');
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileMenuClose = document.getElementById('mobile-menu-close');

    if (!navbar) return;

    // Ensure navbar is sticky
    navbar.style.position = 'fixed';
    navbar.style.top = '0';
    navbar.style.left = '0';
    navbar.style.right = '0';
    navbar.style.zIndex = '50';

    function handleScroll() {
        if (window.scrollY > 20) {
            navbar.classList.add('navbar-scrolled');
            navbar.classList.remove('navbar-default');
        } else {
            navbar.classList.remove('navbar-scrolled');
            navbar.classList.add('navbar-default');
        }
    }

    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

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
   Hero Slider - Simple Auto-slide
   ---------------------------------------- */
function initHeroSlider() {
    const slides = document.querySelectorAll('.hero-slide');
    const dots = document.querySelectorAll('.slider-dot');

    if (slides.length === 0) return;

    let currentIndex = 0;
    const totalSlides = slides.length;
    const autoSlideInterval = 6000;
    let intervalId;

    function showSlide(index) {
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

        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
            dot.classList.toggle('inactive', i !== index);
        });

        currentIndex = index;
    }

    function nextSlide() {
        showSlide((currentIndex + 1) % totalSlides);
    }

    function startAutoSlide() {
        intervalId = setInterval(nextSlide, autoSlideInterval);
    }

    function stopAutoSlide() {
        if (intervalId) clearInterval(intervalId);
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', function () {
            stopAutoSlide();
            showSlide(index);
            startAutoSlide();
        });
    });

    showSlide(0);
    startAutoSlide();
}

/* ----------------------------------------
   Product Slider
   ---------------------------------------- */
function initProductSlider() {
    const scrollContainer = document.getElementById('product-scroll');
    const prevBtn = document.getElementById('product-prev');
    const nextBtn = document.getElementById('product-next');

    if (!scrollContainer) return;

    function updateButtons() {
        const { scrollLeft, scrollWidth, clientWidth } = scrollContainer;
        if (prevBtn) prevBtn.disabled = scrollLeft <= 10;
        if (nextBtn) nextBtn.disabled = scrollLeft >= scrollWidth - clientWidth - 10;
    }

    function scroll(direction) {
        const scrollAmount = scrollContainer.clientWidth * 0.8;
        scrollContainer.scrollBy({
            left: direction === 'left' ? -scrollAmount : scrollAmount,
            behavior: 'smooth'
        });
    }

    if (prevBtn) prevBtn.addEventListener('click', () => scroll('left'));
    if (nextBtn) nextBtn.addEventListener('click', () => scroll('right'));

    scrollContainer.addEventListener('scroll', updateButtons, { passive: true });
    updateButtons();
}

/* ----------------------------------------
   Chat Widget
   ---------------------------------------- */
function initChatWidget() {
    const chatButton = document.getElementById('chat-button');
    const chatWidget = document.getElementById('chat-widget');
    const chatClose = document.getElementById('chat-close');

    if (!chatButton || !chatWidget) return;

    chatButton.addEventListener('click', () => {
        chatWidget.classList.add('open');
        chatWidget.classList.remove('closed');
        chatButton.classList.add('hidden');
        chatButton.classList.remove('visible');
    });

    if (chatClose) {
        chatClose.addEventListener('click', () => {
            chatWidget.classList.remove('open');
            chatWidget.classList.add('closed');
            chatButton.classList.remove('hidden');
            chatButton.classList.add('visible');
        });
    }
}

/* ----------------------------------------
   Scroll Reveal - Using Intersection Observer
   ---------------------------------------- */
function initScrollReveal() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger-item, .curtain-reveal, .title-underline');

    if (revealElements.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -50px 0px', threshold: 0.1 });

    revealElements.forEach(el => observer.observe(el));

    // Stagger containers
    document.querySelectorAll('.stagger-container').forEach(container => {
        const items = container.querySelectorAll('.stagger-item');
        const containerObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    items.forEach(item => item.classList.add('revealed'));
                    containerObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.05 });
        containerObserver.observe(container);
    });
}

/* ----------------------------------------
   Magnetic Buttons - Simplified
   ---------------------------------------- */
function initMagneticButtons() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    document.querySelectorAll('.btn-magnetic').forEach(button => {
        button.addEventListener('mouseenter', () => {
            button.style.transition = 'transform 0.2s ease';
        });

        button.addEventListener('mouseleave', () => {
            button.style.transform = '';
        });
    });
}

/* ----------------------------------------
   Ripple Effect
   ---------------------------------------- */
function initRippleEffect() {
    document.querySelectorAll('.btn-ripple').forEach(button => {
        button.addEventListener('click', function (e) {
            const rect = button.getBoundingClientRect();
            const ripple = document.createElement('span');
            ripple.className = 'ripple';
            ripple.style.left = (e.clientX - rect.left) + 'px';
            ripple.style.top = (e.clientY - rect.top) + 'px';
            button.appendChild(ripple);
            setTimeout(() => ripple.remove(), 500);
        });
    });
}

/* ----------------------------------------
   Smooth Counter Animation
   ---------------------------------------- */
function initSmoothCounters() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const counters = document.querySelectorAll('.count-up');
    if (counters.length === 0) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.dataset.target, 10);
                const suffix = counter.dataset.suffix || '';

                if (!isNaN(target)) {
                    animateCounter(counter, 0, target, 1500, suffix);
                }
                observer.unobserve(counter);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter));
}

function animateCounter(element, start, end, duration, suffix) {
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easeOut = 1 - Math.pow(1 - progress, 3);
        element.textContent = Math.round(start + (end - start) * easeOut).toLocaleString() + suffix;

        if (progress < 1) requestAnimationFrame(update);
    }

    requestAnimationFrame(update);
}
