/* 
==================================================
Orbit Star Services - Custom JavaScript
================================================== 
*/

document.addEventListener('DOMContentLoaded', function() {
    
    // 1. Initialize AOS Animations
    AOS.init({
        duration: 800,
        easing: 'ease-in-out',
        once: true,
        offset: 100
    });

    // 2. Sticky Navbar on Scroll
    const navbar = document.getElementById('navbar');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.add('scrolled'); // Remove this class if we want transparent at top, but for white background logo we might want it always visible or handle differently.
            // Let's actually use 'scrolled' class when scrolling down
            if (window.scrollY <= 50) {
                navbar.classList.remove('scrolled');
            }
        }
    });

    // Run once on load to check scroll position
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    }

    // 3. Counter Animation
    const counters = document.querySelectorAll('.counter');
    const speed = 200; // The lower the slower

    const animateCounters = () => {
        counters.forEach(counter => {
            const updateCount = () => {
                const target = +counter.getAttribute('data-target');
                const count = +counter.innerText;
                const inc = target / speed;

                if (count < target) {
                    counter.innerText = Math.ceil(count + inc);
                    setTimeout(updateCount, 20);
                } else {
                    counter.innerText = target;
                }
            };

            // Only run if the element is in viewport
            const rect = counter.getBoundingClientRect();
            if(rect.top < window.innerHeight && rect.bottom >= 0) {
                if(counter.innerText == '0') {
                    updateCount();
                }
            }
        });
    };

    // Run on scroll for counters
    window.addEventListener('scroll', animateCounters);
    // Run on init in case it's already visible
    animateCounters();

    // 4. Back to Top Button
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    });

    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });

    // 5. Set Current Year in Footer
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // 6. Initialize Owl Carousel for Testimonials
    if($('.testimonial-slider').length) {
        $('.testimonial-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                1000: {
                    items: 3
                }
            }
        });
    }

    // Initialize Owl Carousel for Industries Slider
    if($('.industries-slider').length) {
        $('.industries-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                576: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                }
            }
        });
    }
    // Initialize Owl Carousel for Services Slider
    if($('.services-slider').length) {
        $('.services-slider').owlCarousel({
            loop: true,
            margin: 30,
            nav: false,
            dots: true,
            autoplay: true,
            autoplayTimeout: 4000,
            autoplayHoverPause: true,
            responsive: {
                0: {
                    items: 1
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });
    }

    // 7. Navbar Mobile Close on Click (Optional enhancement)
    const navLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)');
    const navbarCollapse = document.getElementById('mainNav');
    
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (navbarCollapse.classList.contains('show')) {
                // Use Bootstrap's offcanvas/collapse JS API to hide
                const bsCollapse = new bootstrap.Collapse(navbarCollapse, {
                    toggle: false
                });
                bsCollapse.hide();
            }
        });
    });

    // 8. Free Consultation Form Submit Handler
    const consultationForm = document.getElementById('freeConsultationForm');
    if (consultationForm) {
        consultationForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page reload
            
            // Get the submit button and change text to show processing
            const submitBtn = consultationForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Sending... <i class="fas fa-spinner fa-spin ms-2"></i>';
            submitBtn.disabled = true;

            // Simulate a short network request then show SweetAlert
            setTimeout(() => {
                // Show Success SweetAlert
                Swal.fire({
                    title: 'Thank You!',
                    text: 'Your request has been submitted successfully. Our corporate advisors will contact you shortly.',
                    icon: 'success',
                    confirmButtonColor: '#0a192f', // Matches corporate primary color
                    confirmButtonText: 'Done',
                    customClass: {
                        popup: 'rounded-4 border-0 shadow'
                    }
                }).then(() => {
                    // Close the Bootstrap modal
                    const modalEl = document.getElementById('freeConsultationModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                    
                    // Reset form and button
                    consultationForm.reset();
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            }, 800); // 800ms simulated delay
        });
    }
});
