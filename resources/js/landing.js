/* ============================================
   LUX Landing Page - JavaScript
   ============================================ */

document.addEventListener('DOMContentLoaded', function () {
    initNavbar();
    initHeroSlider();
    initProductSlider();
    initChatWidget();
    initScrollReveal();
    initParallax();
    initMagneticButtons();
    initSmoothCounters();
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

/* ----------------------------------------
   Scroll Reveal - Using Intersection Observer
   Highly performant, no scroll event spam
   ---------------------------------------- */
function initScrollReveal() {
    // Check for reduced motion preference
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    // Select all elements that should be revealed on scroll
    const revealElements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale, .stagger-item, .curtain-reveal, .title-underline');

    if (revealElements.length === 0) return;

    // Create Intersection Observer
    const observerOptions = {
        root: null, // viewport
        rootMargin: '0px 0px -80px 0px', // Trigger slightly before element enters viewport
        threshold: 0.1 // Trigger when 10% visible
    };

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                // Unobserve after revealing (one-time animation)
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    // Observe all reveal elements
    revealElements.forEach(element => {
        revealObserver.observe(element);
    });

    // Special handling for stagger containers
    const staggerContainers = document.querySelectorAll('.stagger-container');
    staggerContainers.forEach(container => {
        const items = container.querySelectorAll('.stagger-item');

        const containerObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    items.forEach(item => item.classList.add('revealed'));
                    containerObserver.unobserve(entry.target);
                }
            });
        }, { ...observerOptions, threshold: 0.05 });

        containerObserver.observe(container);
    });
}

/* ----------------------------------------
   Parallax Effect - Using requestAnimationFrame
   GPU-accelerated, smooth scrolling effect
   ---------------------------------------- */
function initParallax() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    const parallaxElements = document.querySelectorAll('.parallax-image');
    if (parallaxElements.length === 0) return;

    let ticking = false;

    function updateParallax() {
        const scrollY = window.scrollY;

        parallaxElements.forEach(element => {
            const parent = element.closest('.parallax-container');
            if (!parent) return;

            const rect = parent.getBoundingClientRect();
            const viewportHeight = window.innerHeight;

            // Only apply parallax when element is in viewport
            if (rect.bottom > 0 && rect.top < viewportHeight) {
                const elementCenter = rect.top + rect.height / 2;
                const viewportCenter = viewportHeight / 2;
                const distance = elementCenter - viewportCenter;
                const parallaxSpeed = 0.15;
                const translateY = distance * parallaxSpeed;

                element.style.transform = `translateY(${translateY}px) scale(1.15)`;
            }
        });

        ticking = false;
    }

    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    updateParallax(); // Initial call
}

/* ----------------------------------------
   Magnetic Buttons - Subtle hover effect
   ---------------------------------------- */
function initMagneticButtons() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    const magneticButtons = document.querySelectorAll('.btn-magnetic');

    magneticButtons.forEach(button => {
        button.addEventListener('mousemove', (e) => {
            const rect = button.getBoundingClientRect();
            const x = e.clientX - rect.left - rect.width / 2;
            const y = e.clientY - rect.top - rect.height / 2;

            // Subtle magnetic effect
            const moveX = x * 0.2;
            const moveY = y * 0.2;

            button.style.transform = `translate(${moveX}px, ${moveY}px)`;
        });

        button.addEventListener('mouseleave', () => {
            button.style.transform = 'translate(0, 0)';
        });
    });
}

/* ----------------------------------------
   Smooth Counter Animation
   ---------------------------------------- */
function initSmoothCounters() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    const counters = document.querySelectorAll('.count-up');
    if (counters.length === 0) return;

    const observerOptions = {
        root: null,
        threshold: 0.5
    };

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'), 10);
                const duration = parseInt(counter.getAttribute('data-duration') || '2000', 10);
                const suffix = counter.getAttribute('data-suffix') || '';

                if (isNaN(target)) return;

                animateCounter(counter, 0, target, duration, suffix);
                counterObserver.unobserve(counter);
            }
        });
    }, observerOptions);

    counters.forEach(counter => counterObserver.observe(counter));
}

function animateCounter(element, start, end, duration, suffix) {
    const startTime = performance.now();

    function update(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);

        // Easing function (ease-out)
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const current = Math.round(start + (end - start) * easeOut);

        element.textContent = current.toLocaleString() + suffix;

        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

/* ----------------------------------------
   Cursor Trail Effect (Optional - Enable manually)
   ---------------------------------------- */
function initCursorTrail() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    if (prefersReducedMotion) return;

    const trail = document.createElement('div');
    trail.className = 'cursor-trail';
    document.body.appendChild(trail);

    let mouseX = 0;
    let mouseY = 0;
    let trailX = 0;
    let trailY = 0;

    document.addEventListener('mousemove', (e) => {
        mouseX = e.clientX;
        mouseY = e.clientY;
    });

    function animateTrail() {
        trailX += (mouseX - trailX) * 0.1;
        trailY += (mouseY - trailY) * 0.1;

        trail.style.left = trailX + 'px';
        trail.style.top = trailY + 'px';

        requestAnimationFrame(animateTrail);
    }

    animateTrail();
}
