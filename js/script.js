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

    // 8. Dynamic Google reCAPTCHA v2 Injection & Script Load
    const consultationForm = document.getElementById('freeConsultationForm');
    if (consultationForm) {
        const submitBtn = consultationForm.querySelector('button[type="submit"]');
        if (submitBtn) {
            const captchaWrapper = document.createElement('div');
            captchaWrapper.className = 'mb-3 d-flex justify-content-center';
            captchaWrapper.innerHTML = '<div class="g-recaptcha" id="recaptcha-consultation" data-sitekey="6Ld8bz0tAAAAAENSxtrcBwRs65IPNkjKLlLxDfaR"></div>';
            submitBtn.parentNode.insertBefore(captchaWrapper, submitBtn);
        }
    }

    const contactForm = document.querySelector('.contact-form-wrapper form');
    if (contactForm) {
        const submitBtnWrapper = contactForm.querySelector('button[type="submit"]').closest('.col-12');
        if (submitBtnWrapper) {
            const captchaWrapper = document.createElement('div');
            captchaWrapper.className = 'col-12 mb-3 d-flex justify-content-center justify-content-md-start';
            captchaWrapper.innerHTML = '<div class="g-recaptcha" id="recaptcha-contact" data-sitekey="6Ld8bz0tAAAAAENSxtrcBwRs65IPNkjKLlLxDfaR"></div>';
            submitBtnWrapper.parentNode.insertBefore(captchaWrapper, submitBtnWrapper);
        }
    }

    // Load Google reCAPTCHA script dynamically
    if (consultationForm || contactForm) {
        const recaptchaScript = document.createElement('script');
        recaptchaScript.src = 'https://www.google.com/recaptcha/api.js';
        recaptchaScript.async = true;
        recaptchaScript.defer = true;
        document.head.appendChild(recaptchaScript);
    }

    // Free Consultation Form Submit Handler
    if (consultationForm) {
        consultationForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent page reload
            
            // Get the submit button and change text to show processing
            const submitBtn = consultationForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Sending... <i class="fas fa-spinner fa-spin ms-2"></i>';
            submitBtn.disabled = true;

            // Get reCAPTCHA response
            const recaptchaResponseEl = consultationForm.querySelector('.g-recaptcha-response');
            const recaptchaResponse = recaptchaResponseEl ? recaptchaResponseEl.value : '';

            if (!recaptchaResponse) {
                Swal.fire({
                    title: 'reCAPTCHA Required',
                    text: 'Please verify that you are not a robot by checking the reCAPTCHA box.',
                    icon: 'warning',
                    confirmButtonColor: '#000056'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                return;
            }

            // Get form values (based on input types since we don't have name attributes)
            const nameInput = consultationForm.querySelector('input[type="text"]');
            const emailInput = consultationForm.querySelector('input[type="email"]');
            const phoneInput = consultationForm.querySelector('input[type="tel"]');
            const messageInput = consultationForm.querySelector('textarea');

            const formData = new URLSearchParams();
            formData.append('name', nameInput ? nameInput.value : '');
            formData.append('email', emailInput ? emailInput.value : '');
            formData.append('phone', phoneInput ? phoneInput.value : '');
            formData.append('message', messageInput ? messageInput.value : '');
            formData.append('services', 'Free Consultation');
            formData.append('code', 'N/A');
            formData.append('g-recaptcha-response', recaptchaResponse);

            // Send actual network request using fetch
            fetch('quote_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Thank You!',
                        text: 'Your request has been submitted successfully. Our corporate advisors will contact you shortly.',
                        icon: 'success',
                        confirmButtonColor: '#000056', // Matches corporate primary color
                        confirmButtonText: 'Done',
                        customClass: {
                            popup: 'rounded-4 border-0 shadow'
                        }
                    }).then(() => {
                        // Close the Bootstrap modal
                        const modalEl = document.getElementById('freeConsultationModal');
                        let modalInstance = bootstrap.Modal.getInstance(modalEl);
                        if (!modalInstance && modalEl) {
                            modalInstance = new bootstrap.Modal(modalEl);
                        }
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                        
                        // Reset form
                        consultationForm.reset();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#000056'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Unable to send request. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#000056'
                });
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                if (typeof grecaptcha !== 'undefined') {
                    try { grecaptcha.reset(0); } catch(e) {
                        try { grecaptcha.reset(); } catch(err) {}
                    }
                }
            });
        });
    }

    // 9. Contact Form Submit Handler
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Sending... <i class="fas fa-spinner fa-spin ms-2"></i>';
            submitBtn.disabled = true;

            // Get reCAPTCHA response
            const recaptchaResponseEl = contactForm.querySelector('.g-recaptcha-response');
            const recaptchaResponse = recaptchaResponseEl ? recaptchaResponseEl.value : '';

            if (!recaptchaResponse) {
                Swal.fire({
                    title: 'reCAPTCHA Required',
                    text: 'Please verify that you are not a robot by checking the reCAPTCHA box.',
                    icon: 'warning',
                    confirmButtonColor: '#000056'
                });
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                return;
            }

            // Get form values
            const nameInput = contactForm.querySelector('input[type="text"]');
            const emailInput = contactForm.querySelector('input[type="email"]');
            const phoneInput = contactForm.querySelector('input[type="tel"]');
            const serviceSelect = contactForm.querySelector('select');
            const messageInput = contactForm.querySelector('textarea');

            // Map selected service value/text as subject
            let selectedServiceText = "New Inquiry";
            if (serviceSelect && serviceSelect.selectedIndex > 0) {
                selectedServiceText = serviceSelect.options[serviceSelect.selectedIndex].text;
            }

            const formData = new URLSearchParams();
            formData.append('name', nameInput ? nameInput.value : '');
            formData.append('email', emailInput ? emailInput.value : '');
            formData.append('phone', phoneInput ? phoneInput.value : '');
            formData.append('subject', selectedServiceText);
            formData.append('message', messageInput ? messageInput.value : '');
            formData.append('g-recaptcha-response', recaptchaResponse);

            fetch('contact_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: formData.toString()
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: 'Your query has been sent successfully. We will get back to you shortly.',
                        icon: 'success',
                        confirmButtonColor: '#000056',
                        confirmButtonText: 'Done',
                        customClass: {
                            popup: 'rounded-4 border-0 shadow'
                        }
                    }).then(() => {
                        contactForm.reset();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message || 'Something went wrong. Please try again.',
                        icon: 'error',
                        confirmButtonColor: '#000056'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Unable to send inquiry. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#000056'
                });
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                if (typeof grecaptcha !== 'undefined') {
                    try { grecaptcha.reset(1); } catch(e) {
                        try { grecaptcha.reset(); } catch(err) {}
                    }
                }
            });
        });
    }
});
