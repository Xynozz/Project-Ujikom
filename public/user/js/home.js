document.addEventListener('DOMContentLoaded', function() {
    const navbar = document.querySelector('.transparent-navbar');
    const navbarCollapse = document.querySelector('.navbar-collapse');

    // Handle scroll and zoom events
    function handleNavbarVisibility() {
        // Check scroll position
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }

        // Check window width for responsive behavior
        if (window.innerWidth <= 991.98) {
            navbar.classList.add('mobile-nav');
        } else {
            navbar.classList.remove('mobile-nav');
            // Reset mobile menu state when switching to desktop
            if (navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.remove('show');
            }
        }
    }

    // Initialize carousel
    const carousel = new bootstrap.Carousel(document.querySelector('#heroCarousel'), {
        interval: 5000,
        ride: true,
        touch: true,
        pause: 'hover'
    });

    // Handle caption animations
    document.querySelector('#heroCarousel').addEventListener('slide.bs.carousel', function(e) {
        const currentCaption = e.relatedTarget.querySelector('.carousel-caption');
        currentCaption.style.opacity = '0';
        setTimeout(() => {
            currentCaption.style.opacity = '1';
        }, 600);
    });

    // Event listeners for scroll and resize
    window.addEventListener('scroll', handleNavbarVisibility);
    window.addEventListener('resize', handleNavbarVisibility);

    // Close mobile menu on link click
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
                navbarCollapse.classList.remove('show');
            }
        });
    });

    // Initial check for navbar state
    handleNavbarVisibility();

    const ticketsWrapper = document.getElementById('ticketsWrapper');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');

    // Scroll amount for each click (width of card + gap)
    const scrollAmount = 260; // card width + gap

    if (prevBtn && nextBtn && ticketsWrapper) {
        // Previous button click handler
        prevBtn.addEventListener('click', () => {
            ticketsWrapper.scrollBy({
                left: -scrollAmount,
                behavior: 'smooth'
            });
        });

        // Next button click handler
        nextBtn.addEventListener('click', () => {
            ticketsWrapper.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        });

        // Show/hide buttons based on scroll position
        ticketsWrapper.addEventListener('scroll', () => {
            const isAtStart = ticketsWrapper.scrollLeft === 0;
            const isAtEnd = ticketsWrapper.scrollLeft + ticketsWrapper.clientWidth >= ticketsWrapper.scrollWidth;

            prevBtn.style.opacity = isAtStart ? '0.5' : '1';
            prevBtn.style.cursor = isAtStart ? 'not-allowed' : 'pointer';

            nextBtn.style.opacity = isAtEnd ? '0.5' : '1';
            nextBtn.style.cursor = isAtEnd ? 'not-allowed' : 'pointer';
        });
    }
});