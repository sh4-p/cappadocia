/**
 * Main JavaScript file for Cappadocia Travel Agency
 */

// Wait for the DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Initialize all components
    preloaderInit();
    headerInit();
    searchInit();
    backToTopInit();
    sliderInit();
    galleryInit();
    dropdownInit();
    alertInit();
    tourTabsInit();
    bookingFormInit();
    ajaxSearchInit();
    
    // Call AOS (Animate On Scroll) if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
    }
});

/**
 * Initialize preloader
 */
function preloaderInit() {
    const preloader = document.querySelector('.preloader');
    
    if (preloader) {
        // Hide preloader after page load
        window.addEventListener('load', function() {
            preloader.style.opacity = '0';
            setTimeout(function() {
                preloader.style.display = 'none';
            }, 500);
        });
    }
}

/**
 * Initialize header effects
 */
function headerInit() {
    const header = document.querySelector('.site-header');
    const mobileToggle = document.querySelector('.mobile-toggle');
    const mainNav = document.querySelector('.main-nav');
    
    if (header) {
        // Add scrolled class to header when scrolling
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    if (mobileToggle && mainNav) {
        // Toggle mobile menu
        mobileToggle.addEventListener('click', function() {
            mobileToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!mainNav.contains(event.target) && !mobileToggle.contains(event.target)) {
                mobileToggle.classList.remove('active');
                mainNav.classList.remove('active');
            }
        });
    }
}

/**
 * Initialize search overlay
 */
function searchInit() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchOverlay = document.querySelector('.search-overlay');
    const searchClose = document.querySelector('.search-close');
    
    if (searchToggle && searchOverlay && searchClose) {
        // Open search overlay
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchOverlay.classList.add('active');
            setTimeout(function() {
                document.querySelector('#search-input').focus();
            }, 100);
            document.body.style.overflow = 'hidden';
        });
        
        // Close search overlay
        searchClose.addEventListener('click', function() {
            searchOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Close search overlay on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
}

/**
 * Initialize back to top button
 */
function backToTopInit() {
    const backToTop = document.querySelector('.back-to-top');
    
    if (backToTop) {
        // Show/hide back to top button
        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTop.classList.add('active');
            } else {
                backToTop.classList.remove('active');
            }
        });
        
        // Scroll to top on click
        backToTop.addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

/**
 * Initialize sliders
 */
function sliderInit() {
    // Testimonial slider
    const testimonialSlider = document.querySelector('.testimonial-slider');
    if (testimonialSlider) {
        const slides = testimonialSlider.querySelectorAll('.testimonial-slide');
        const prevBtn = testimonialSlider.querySelector('.testimonial-prev');
        const nextBtn = testimonialSlider.querySelector('.testimonial-next');
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        
        // Show current slide
        function showSlide(index) {
            slides.forEach((slide) => {
                slide.style.display = 'none';
            });
            
            slides[index].style.display = 'block';
        }
        
        // Initialize slider
        if (slides.length > 0) {
            showSlide(currentSlide);
            
            // Previous button
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    showSlide(currentSlide);
                });
            }
            
            // Next button
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showSlide(currentSlide);
                });
            }
            
            // Auto slide
            setInterval(function() {
                if (document.hasFocus()) {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showSlide(currentSlide);
                }
            }, 5000);
        }
    }
    
    // Hero slider
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        const slides = heroSlider.querySelectorAll('.hero-slide');
        const dots = heroSlider.querySelectorAll('.hero-dot');
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        
        // Show current slide
        function showHeroSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = 'none';
                if (dots[i]) {
                    dots[i].classList.remove('active');
                }
            });
            
            slides[index].style.display = 'block';
            if (dots[index]) {
                dots[index].classList.add('active');
            }
        }
        
        // Initialize slider
        if (slides.length > 0) {
            showHeroSlide(currentSlide);
            
            // Dots navigation
            dots.forEach((dot, index) => {
                dot.addEventListener('click', function() {
                    currentSlide = index;
                    showHeroSlide(currentSlide);
                });
            });
            
            // Auto slide
            setInterval(function() {
                if (document.hasFocus()) {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showHeroSlide(currentSlide);
                }
            }, 5000);
        }
    }
}

/**
 * Initialize gallery lightbox
 */
function galleryInit() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    if (galleryItems.length > 0) {
        // Create lightbox elements
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        
        const lightboxContent = document.createElement('div');
        lightboxContent.className = 'lightbox-content';
        
        const lightboxImage = document.createElement('img');
        lightboxImage.className = 'lightbox-image';
        
        const lightboxClose = document.createElement('button');
        lightboxClose.className = 'lightbox-close';
        lightboxClose.innerHTML = '<i class="material-icons">close</i>';
        
        const lightboxPrev = document.createElement('button');
        lightboxPrev.className = 'lightbox-prev';
        lightboxPrev.innerHTML = '<i class="material-icons">keyboard_arrow_left</i>';
        
        const lightboxNext = document.createElement('button');
        lightboxNext.className = 'lightbox-next';
        lightboxNext.innerHTML = '<i class="material-icons">keyboard_arrow_right</i>';
        
        const lightboxCaption = document.createElement('div');
        lightboxCaption.className = 'lightbox-caption';
        
        // Append elements to lightbox
        lightboxContent.appendChild(lightboxImage);
        lightboxContent.appendChild(lightboxCaption);
        lightbox.appendChild(lightboxContent);
        lightbox.appendChild(lightboxClose);
        lightbox.appendChild(lightboxPrev);
        lightbox.appendChild(lightboxNext);
        
        // Add lightbox to body
        document.body.appendChild(lightbox);
        
        let currentIndex = 0;
        const galleryImages = [];
        
        // Collect gallery images and captions
        galleryItems.forEach((item, index) => {
            const image = item.querySelector('img');
            const caption = image.getAttribute('alt') || '';
            
            galleryImages.push({
                src: image.getAttribute('src'),
                caption: caption
            });
            
            // Open lightbox on click
            item.addEventListener('click', function(e) {
                e.preventDefault();
                openLightbox(index);
            });
        });
        
        // Open lightbox
        function openLightbox(index) {
            currentIndex = index;
            updateLightbox();
            lightbox.style.display = 'flex';
            setTimeout(function() {
                lightbox.style.opacity = '1';
            }, 10);
            document.body.style.overflow = 'hidden';
        }
        
        // Close lightbox
        function closeLightbox() {
            lightbox.style.opacity = '0';
            setTimeout(function() {
                lightbox.style.display = 'none';
            }, 300);
            document.body.style.overflow = '';
        }
        
        // Update lightbox content
        function updateLightbox() {
            const image = galleryImages[currentIndex];
            lightboxImage.src = image.src;
            lightboxCaption.textContent = image.caption;
            
            // Preload next and previous images
            if (currentIndex > 0) {
                const prevImage = new Image();
                prevImage.src = galleryImages[currentIndex - 1].src;
            }
            
            if (currentIndex < galleryImages.length - 1) {
                const nextImage = new Image();
                nextImage.src = galleryImages[currentIndex + 1].src;
            }
        }
        
        // Previous image
        function prevImage() {
            currentIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
            updateLightbox();
        }
        
        // Next image
        function nextImage() {
            currentIndex = (currentIndex + 1) % galleryImages.length;
            updateLightbox();
        }
        
        // Event listeners
        lightboxClose.addEventListener('click', closeLightbox);
        lightboxPrev.addEventListener('click', prevImage);
        lightboxNext.addEventListener('click', nextImage);
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (lightbox.style.display === 'flex') {
                if (e.key === 'Escape') {
                    closeLightbox();
                } else if (e.key === 'ArrowLeft') {
                    prevImage();
                } else if (e.key === 'ArrowRight') {
                    nextImage();
                }
            }
        });
        
        // Close lightbox on outside click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    }
}

/**
 * Initialize dropdowns
 */
function dropdownInit() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach((dropdown) => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (toggle && menu) {
            // Show dropdown on click
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Close all other dropdowns
                dropdowns.forEach((otherDropdown) => {
                    if (otherDropdown !== dropdown) {
                        otherDropdown.querySelector('.dropdown-menu').classList.remove('active');
                    }
                });
                
                // Toggle current dropdown
                menu.classList.toggle('active');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!menu.contains(e.target) && !toggle.contains(e.target)) {
                    menu.classList.remove('active');
                }
            });
        }
    });
}

/**
 * Initialize alert messages
 */
function alertInit() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach((alert) => {
        const closeBtn = alert.querySelector('.alert-close');
        
        if (closeBtn) {
            // Close alert on button click
            closeBtn.addEventListener('click', function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            });
            
            // Auto close after 5 seconds
            setTimeout(function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                }, 300);
            }, 5000);
        }
    });
}

/**
 * Initialize tour tabs
 */
function tourTabsInit() {
    const tourTabs = document.querySelector('.tour-tabs');
    
    if (tourTabs) {
        const tabButtons = tourTabs.querySelectorAll('.tour-tab-button');
        const tabContents = tourTabs.querySelectorAll('.tour-tab-content');
        
        // Initialize tabs
        if (tabButtons.length > 0 && tabContents.length > 0) {
            // Set first tab as active by default
            tabButtons[0].classList.add('active');
            tabContents[0].classList.add('active');
            
            // Tab button click event
            tabButtons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons and contents
                    tabButtons.forEach((btn) => btn.classList.remove('active'));
                    tabContents.forEach((content) => content.classList.remove('active'));
                    
                    // Add active class to current button and content
                    button.classList.add('active');
                    tabContents[index].classList.add('active');
                });
            });
        }
    }
}

/**
 * Initialize booking form
 */
function bookingFormInit() {
    const bookingForm = document.querySelector('.booking-form');
    
    if (bookingForm) {
        // Calculate total price
        function calculateTotalPrice() {
            const adults = parseInt(document.getElementById('booking_adults').value) || 0;
            const children = parseInt(document.getElementById('booking_children').value) || 0;
            const basePrice = parseFloat(document.getElementById('booking_base_price').value) || 0;
            const discountPrice = parseFloat(document.getElementById('booking_discount_price').value) || 0;
            
            const price = discountPrice > 0 ? discountPrice : basePrice;
            const totalPrice = (adults * price) + (children * price * 0.5);
            
            document.getElementById('booking_total_price').value = totalPrice.toFixed(2);
            document.getElementById('booking_price_display').textContent = totalPrice.toFixed(2);
        }
        
        // Get inputs
        const adultsInput = document.getElementById('booking_adults');
        const childrenInput = document.getElementById('booking_children');
        
        if (adultsInput && childrenInput) {
            // Calculate initial total price
            calculateTotalPrice();
            
            // Update total price when inputs change
            adultsInput.addEventListener('change', calculateTotalPrice);
            childrenInput.addEventListener('change', calculateTotalPrice);
        }
        
        // Form validation
        bookingForm.addEventListener('submit', function(e) {
            const required = bookingForm.querySelectorAll('[required]');
            let valid = true;
            
            required.forEach((field) => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                
                // Scroll to first invalid field
                const firstInvalid = bookingForm.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstInvalid.focus();
                }
            }
        });
    }
}

/**
 * Initialize Ajax search
 */
function ajaxSearchInit() {
    const searchInput = document.getElementById('search-input');
    const searchResults = document.getElementById('search-results');
    
    if (searchInput && searchResults) {
        let searchTimeout;
        
        // Search input event
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Empty results if query is empty
            if (query === '') {
                searchResults.innerHTML = '';
                return;
            }
            
            // Set timeout to prevent too many requests
            searchTimeout = setTimeout(function() {
                // Make Ajax request
                fetch(`${window.location.origin}/${searchInput.getAttribute('data-lang') || 'en'}/tours/ajax-search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        // Clear results
                        searchResults.innerHTML = '';
                        
                        if (data.length > 0) {
                            // Create results
                            data.forEach(tour => {
                                const result = document.createElement('div');
                                result.className = 'search-result';
                                
                                const resultLink = document.createElement('a');
                                resultLink.href = tour.url;
                                
                                const resultTitle = document.createElement('h3');
                                resultTitle.textContent = tour.name;
                                
                                const resultPrice = document.createElement('p');
                                if (tour.discount_price) {
                                    resultPrice.innerHTML = `<del>${tour.price}</del> ${tour.discount_price}`;
                                } else {
                                    resultPrice.textContent = tour.price;
                                }
                                
                                resultLink.appendChild(resultTitle);
                                resultLink.appendChild(resultPrice);
                                result.appendChild(resultLink);
                                searchResults.appendChild(result);
                            });
                        } else {
                            // No results
                            const noResults = document.createElement('div');
                            noResults.className = 'search-no-results';
                            noResults.textContent = 'No tours found. Try a different search.';
                            searchResults.appendChild(noResults);
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                    });
            }, 300);
        });
    }
}