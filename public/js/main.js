/**
 * Cappadocia Travel Agency - Modern Redesign
 * Main JavaScript
 * 
 * Enhanced user experience with smooth animations and interactions
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
    countUpInit();
    bookingFormInit();
    
    // Initialize new tour filter functionality
    tourFilterInit();
    
    // Call AOS (Animate On Scroll) if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out',
            once: true,
            offset: 50,
            delay: 50
        });
    }
});

/**
 * Initialize tour filters functionality
 */
function tourFilterInit() {
    // Initialize filter toggle functionality
    initFilterToggle();
    
    // Initialize price range slider
    initPriceRangeSlider();
    
    // Initialize filter form auto submit
    initFilterAutoSubmit();
    
    // Initialize mobile filter handling
    initMobileFilters();
    
    // Initialize filter reset
    initFilterReset();
    
    // Equalize card heights
    equalizeCardHeights();
}

/**
 * Function to initialize filter toggle
 */
function initFilterToggle() {
    const filterHeaders = document.querySelectorAll('.filter-header');
    
    filterHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            // Önemli: Form gönderimini engelle
            e.preventDefault();
            
            const filterGroup = this.closest('.filter-group');
            
            // Toggle open class
            filterGroup.classList.toggle('open');
            
            // Find filter body
            const filterBody = filterGroup.querySelector('.filter-body');
            
            // Toggle display
            if (filterGroup.classList.contains('open')) {
                filterBody.style.display = 'block';
            } else {
                filterBody.style.display = 'none';
            }
            
            // Event'in form'a yayılmasını engelle
            e.stopPropagation();
            
            return false;
        });
    });
    
    // Ek olarak, filter-toggle butonlarına tıklamaları da ele alalım
    const filterToggles = document.querySelectorAll('.filter-toggle');
    
    filterToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            // Önemli: Form gönderimini engelle
            e.preventDefault();
            
            // Event'in aşağıya yayılmasını engelle (parent'a tıklama da tetiklenir normalde)
            e.stopPropagation();
            
            // Filtergroup'u bul
            const filterGroup = this.closest('.filter-group');
            
            // Toggle open class
            filterGroup.classList.toggle('open');
            
            // Find filter body
            const filterBody = filterGroup.querySelector('.filter-body');
            
            // Toggle display
            if (filterGroup.classList.contains('open')) {
                filterBody.style.display = 'block';
            } else {
                filterBody.style.display = 'none';
            }
            
            return false;
        });
    });
}

/**
 * Function to initialize price range slider
 */
function initPriceRangeSlider() {
    const priceRangeSlider = document.querySelector('.price-range-slider');
    
    if (priceRangeSlider && typeof jQuery !== 'undefined' && jQuery.ui) {
        const minDisplay = document.querySelector('.price-min-display');
        const maxDisplay = document.querySelector('.price-max-display');
        const minInput = document.getElementById('price_min');
        const maxInput = document.getElementById('price_max');
        
        // Get min/max values from data attributes
        const minValue = parseInt(priceRangeSlider.dataset.min || 0);
        const maxValue = parseInt(priceRangeSlider.dataset.max || 1000);
        const currencySymbol = priceRangeSlider.dataset.currency || '$';
        
        // Initialize jQuery UI slider
        jQuery(priceRangeSlider).slider({
            range: true,
            min: minValue,
            max: maxValue,
            values: [minValue, maxValue],
            slide: function(event, ui) {
                // Update displays
                minDisplay.textContent = currencySymbol + ui.values[0];
                maxDisplay.textContent = currencySymbol + ui.values[1];
                
                // Update hidden inputs
                minInput.value = ui.values[0];
                maxInput.value = ui.values[1];
            },
            change: function(event, ui) {
                // Trigger form submit if auto-submit enabled
                const form = priceRangeSlider.closest('form');
                if (form && form.dataset.autoSubmit === 'true') {
                    form.dispatchEvent(new Event('submit'));
                }
            }
        });
        
        // Set initial values for display
        minDisplay.textContent = currencySymbol + jQuery(priceRangeSlider).slider('values', 0);
        maxDisplay.textContent = currencySymbol + jQuery(priceRangeSlider).slider('values', 1);
        
        // Set initial values for hidden inputs
        minInput.value = jQuery(priceRangeSlider).slider('values', 0);
        maxInput.value = jQuery(priceRangeSlider).slider('values', 1);
    } else {
        // Fallback for when jQuery UI is not available
        console.warn('jQuery UI not available for price range slider');
        
        // Hide price range filter
        const priceRangeGroup = priceRangeSlider?.closest('.filter-group');
        if (priceRangeGroup) {
            priceRangeGroup.style.display = 'none';
        }
    }
}

/**
 * Function to initialize filter form auto submit
 */
function initFilterAutoSubmit() {
    const filterForm = document.querySelector('.tour-filters form');
    
    if (filterForm && filterForm.dataset.autoSubmit === 'true') {
        // Listen for changes on checkboxes and radio buttons
        const formInputs = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"]');
        
        formInputs.forEach(input => {
            input.addEventListener('change', function() {
                // For category checkboxes, we need special handling (single selection)
                if (this.name === 'category') {
                    // Uncheck all other category checkboxes
                    const categoryCheckboxes = filterForm.querySelectorAll('input[name="category"]');
                    categoryCheckboxes.forEach(checkbox => {
                        if (checkbox !== this) {
                            checkbox.checked = false;
                        }
                    });
                }
                
                // Submit the form
                filterForm.submit();
            });
        });
    }
}

/**
 * Function to initialize mobile filter handling
 */
function initMobileFilters() {
    const filterToggle = document.querySelector('.filter-mobile-toggle');
    const filterClose = document.querySelector('.filter-close-mobile');
    const filterPanel = document.querySelector('.tour-filters');
    const body = document.body;
    
    // Create backdrop element if it doesn't exist
    let backdrop = document.querySelector('.menu-backdrop');
    if (!backdrop) {
        backdrop = document.createElement('div');
        backdrop.className = 'menu-backdrop';
        document.body.appendChild(backdrop);
    }
    
    if (filterToggle) {
        filterToggle.addEventListener('click', function() {
            filterPanel.classList.add('filters-open');
            body.classList.add('filters-opened');
            backdrop.classList.add('active');
        });
    }
    
    if (filterClose) {
        filterClose.addEventListener('click', function() {
            filterPanel.classList.remove('filters-open');
            body.classList.remove('filters-opened');
            backdrop.classList.remove('active');
        });
    }
    
    // Close filters when clicking on backdrop
    if (backdrop) {
        backdrop.addEventListener('click', function() {
            filterPanel.classList.remove('filters-open');
            body.classList.remove('filters-opened');
            backdrop.classList.remove('active');
        });
    }
}

/**
 * Function to initialize filter reset
 */
function initFilterReset() {
    const resetBtn = document.querySelector('.filter-reset');
    const filterForm = document.querySelector('.tour-filters form');
    
    if (resetBtn && filterForm) {
        resetBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Reset all form inputs
            const formInputs = filterForm.querySelectorAll('input[type="checkbox"], input[type="radio"]');
            formInputs.forEach(input => {
                input.checked = false;
            });
            
            // Reset price range slider if exists
            const priceRangeSlider = document.querySelector('.price-range-slider');
            if (priceRangeSlider && typeof jQuery !== 'undefined' && jQuery.ui) {
                const minValue = parseInt(priceRangeSlider.dataset.min || 0);
                const maxValue = parseInt(priceRangeSlider.dataset.max || 1000);
                
                jQuery(priceRangeSlider).slider('values', 0, minValue);
                jQuery(priceRangeSlider).slider('values', 1, maxValue);
                
                // Update displays and inputs
                const minDisplay = document.querySelector('.price-min-display');
                const maxDisplay = document.querySelector('.price-max-display');
                const minInput = document.getElementById('price_min');
                const maxInput = document.getElementById('price_max');
                const currencySymbol = priceRangeSlider.dataset.currency || '$';
                
                if (minDisplay) minDisplay.textContent = currencySymbol + minValue;
                if (maxDisplay) maxDisplay.textContent = currencySymbol + maxValue;
                if (minInput) minInput.value = minValue;
                if (maxInput) maxInput.value = maxValue;
            }
            
            // Submit the form to reset the page
            window.location.href = filterForm.action;
        });
    }
}

/**
 * Ensure equal card heights
 */
function equalizeCardHeights() {
    const cards = document.querySelectorAll('.tour-card');
    if (!cards.length) return;
    
    // Reset heights first
    cards.forEach(card => {
        card.style.height = 'auto';
    });
    
    // Only equalize on desktop
    if (window.innerWidth >= 768) {
        // Get max card height
        let maxHeight = 0;
        cards.forEach(card => {
            if (card.offsetHeight > maxHeight) {
                maxHeight = card.offsetHeight;
            }
        });
        
        // Apply max height to all cards
        cards.forEach(card => {
            card.style.height = maxHeight + 'px';
        });
    }
}

/**
 * Initialize preloader with smooth fade-out
 */
function preloaderInit() {
    const preloader = document.querySelector('.preloader');
    
    if (preloader) {
        // Hide preloader after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                preloader.style.opacity = '0';
                setTimeout(function() {
                    preloader.style.display = 'none';
                    
                    // Trigger reveal animations after preloader
                    document.querySelectorAll('.fade-in, .fade-in-up, .fade-in-down, .fade-in-left, .fade-in-right')
                        .forEach(function(element) {
                            element.classList.add('visible');
                        });
                }, 500);
            }, 500);
        });
    }
}

/**
 * Initialize header effects - transparent to solid on scroll
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
        
        // Initially check if page is already scrolled
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        }
    }
    
    if (mobileToggle && mainNav) {
        // Toggle mobile menu
        mobileToggle.addEventListener('click', function() {
            mobileToggle.classList.toggle('active');
            mainNav.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
        
        // Close mobile menu when clicking menu items
        mainNav.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                mobileToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.classList.remove('menu-open');
            });
        });
        
        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            if (mainNav.classList.contains('active') && 
                !mainNav.contains(event.target) && 
                !mobileToggle.contains(event.target)) {
                mobileToggle.classList.remove('active');
                mainNav.classList.remove('active');
                document.body.classList.remove('menu-open');
            }
        });
        
        // Add active class to current menu item
        const currentPath = window.location.pathname;
        mainNav.querySelectorAll('a').forEach(function(link) {
            if (link.getAttribute('href') === currentPath || 
                currentPath.startsWith(link.getAttribute('href'))) {
                link.classList.add('active');
            }
        });
    }
}

/**
 * Initialize search overlay with fade effects
 */
function searchInit() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchOverlay = document.querySelector('.search-overlay');
    const searchClose = document.querySelector('.search-close');
    const searchInput = document.querySelector('#search-input');
    
    if (searchToggle && searchOverlay && searchClose) {
        // Open search overlay
        searchToggle.addEventListener('click', function(e) {
            e.preventDefault();
            searchOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Focus on search input after transition
            setTimeout(function() {
                if (searchInput) searchInput.focus();
            }, 300);
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
        
        // Close on overlay click (not on content)
        searchOverlay.addEventListener('click', function(e) {
            if (e.target === searchOverlay) {
                searchOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
}

/**
 * Initialize quick booking form
 */
function quickBookingInit() {
    const quickSearchForm = document.getElementById('quick-booking-form');
    const searchInput = document.getElementById('quick_booking_keyword');
    const resultsContainer = document.getElementById('quick-search-results');
    
    if (quickSearchForm && searchInput && resultsContainer) {
        let searchTimeout;
        
        // Get app base URL from data attribute
        const appBaseUrl = quickSearchForm.getAttribute('data-app-url') || '';
        
        // Search input event listener
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Clear previous timeout
            clearTimeout(searchTimeout);
            
            // Hide results if query is empty
            if (query.length < 2) {
                resultsContainer.innerHTML = '';
                resultsContainer.style.display = 'none';
                return;
            }
            
            // Set timeout to prevent excessive requests
            searchTimeout = setTimeout(function() {
                // Show loading indicator
                resultsContainer.innerHTML = '<div class="search-loading"><div class="spinner"></div></div>';
                resultsContainer.style.display = 'block';
                
                // Get current language from URL or HTML tag
                const pathParts = window.location.pathname.split('/');
                const currentLang = pathParts[1] && pathParts[1].length === 2 ? pathParts[1] : 
                                  (document.documentElement.lang || 'en');
                
                // Make AJAX request
                fetch(`${appBaseUrl}/${currentLang}/tours/ajax-search?q=${encodeURIComponent(query)}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.length > 0) {
                        // Build results
                        let html = '';
                        
                        data.forEach(function(tour) {
                            // Ensure image URL has correct base path
                            let imageUrl = tour.image;
                            if (imageUrl && !imageUrl.startsWith('http')) {
                                imageUrl = `${appBaseUrl}${imageUrl}`;
                            }
                            
                            html += `
                                <div class="quick-search-result">
                                    <a href="${tour.url}">
                                        <div class="result-image">
                                            <img src="${imageUrl}" alt="${tour.name}">
                                        </div>
                                        <div class="result-content">
                                            <h4>${tour.name}</h4>
                                            <div class="result-price">
                                                ${tour.discount_price ? 
                                                    `<del>${tour.price}</del> ${tour.discount_price}` : 
                                                    tour.price}
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            `;
                        });
                        
                        resultsContainer.innerHTML = html;
                        resultsContainer.style.display = 'block';
                    } else {
                        resultsContainer.innerHTML = '<div class="no-results">Arama kriterlerinize uygun tur bulunamadı.</div>';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    resultsContainer.innerHTML = '<div class="search-error">Bir hata oluştu. Lütfen tekrar deneyin.</div>';
                });
            }, 300);
        });
        
        // Close results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
        
        // Handle result click
        resultsContainer.addEventListener('click', function(e) {
            const resultItem = e.target.closest('.quick-search-result a');
            if (resultItem) {
                e.preventDefault();
                window.location.href = resultItem.getAttribute('href');
            }
        });
        
        // Form submission
        quickSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const keywordValue = searchInput.value.trim();
            const dateValue = document.getElementById('quick_booking_date').value;
            const guestsValue = this.querySelector('select[name="guests"]').value;
            
            // Get current language
            const pathParts = window.location.pathname.split('/');
            const currentLang = pathParts[1] && pathParts[1].length === 2 ? pathParts[1] : 
                              (document.documentElement.lang || 'en');
            
            // Create URL with correct base path
            let url = `${appBaseUrl}/${currentLang}/tours?`;
            
            if (keywordValue) url += `keyword=${encodeURIComponent(keywordValue)}&`;
            if (dateValue) url += `date=${encodeURIComponent(dateValue)}&`;
            if (guestsValue) url += `guests=${encodeURIComponent(guestsValue)}`;
            
            window.location.href = url;
        });
    }
}

/**
 * Initialize back to top button with smooth scroll
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
        
        // Scroll to top on click with smooth animation
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
 * Initialize sliders with fade transitions
 */
function sliderInit() {
    // Testimonial slider
    const testimonialSlider = document.querySelector('.testimonial-slider');
    
    if (testimonialSlider) {
        const slides = testimonialSlider.querySelectorAll('.testimonial-slide');
        const prevBtn = testimonialSlider.querySelector('.testimonial-prev');
        const nextBtn = testimonialSlider.querySelector('.testimonial-next');
        
        if (slides.length <= 1) return;
        
        let currentSlide = 0;
        const totalSlides = slides.length;
        let autoSlideInterval;
        
        // Show slide function with fade effect
        function showSlide(index) {
            // Hide all slides with fade
            slides.forEach(function(slide) {
                slide.style.opacity = '0';
                setTimeout(function() {
                    slide.style.display = 'none';
                }, 300);
            });
            
            // Show current slide with fade
            setTimeout(function() {
                slides[index].style.display = 'block';
                setTimeout(function() {
                    slides[index].style.opacity = '1';
                }, 50);
            }, 300);
            
            currentSlide = index;
        }
        
        // Initialize slider
        slides.forEach(function(slide, index) {
            if (index !== 0) {
                slide.style.display = 'none';
                slide.style.opacity = '0';
            }
        });
        
        // Auto slide function
        function startAutoSlide() {
            autoSlideInterval = setInterval(function() {
                if (document.hasFocus()) {
                    const nextIndex = (currentSlide + 1) % totalSlides;
                    showSlide(nextIndex);
                }
            }, 5000);
        }
        
        // Start auto slide
        startAutoSlide();
        
        // Previous slide button
        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                clearInterval(autoSlideInterval);
                const prevIndex = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(prevIndex);
                startAutoSlide();
            });
        }
        
        // Next slide button
        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                clearInterval(autoSlideInterval);
                const nextIndex = (currentSlide + 1) % totalSlides;
                showSlide(nextIndex);
                startAutoSlide();
            });
        }
        
        // Pause on hover
        testimonialSlider.addEventListener('mouseenter', function() {
            clearInterval(autoSlideInterval);
        });
        
        testimonialSlider.addEventListener('mouseleave', function() {
            startAutoSlide();
        });
    }
    
    // Hero slider (if exists)
    const heroSlider = document.querySelector('.hero-slider');
    if (heroSlider) {
        // Similar implementation as testimonial slider
        // Customize for hero slider specifics
    }
}

/**
 * Initialize gallery with lightbox
 */
function galleryInit() {
    const galleryItems = document.querySelectorAll('.gallery-item, .tour-gallery-item');
    
    if (galleryItems.length > 0) {
        // Create lightbox if not exists
        let lightbox = document.querySelector('.gallery-lightbox');
        
        if (!lightbox) {
            lightbox = document.createElement('div');
            lightbox.className = 'gallery-lightbox';
            
            lightbox.innerHTML = `
                <div class="lightbox-content">
                    <img src="" alt="" class="lightbox-image">
                    <div class="lightbox-caption"></div>
                    <button class="lightbox-close"><i class="material-icons">close</i></button>
                    <button class="lightbox-prev"><i class="material-icons">chevron_left</i></button>
                    <button class="lightbox-next"><i class="material-icons">chevron_right</i></button>
                </div>
            `;
            
            document.body.appendChild(lightbox);
        }
        
        // Collect all gallery images
        const galleryImages = [];
        
        galleryItems.forEach(function(item, index) {
            const imageElement = item.querySelector('img');
            const imageLink = item.querySelector('a') || item;
            
            if (imageElement) {
                const imageSrc = imageLink.href || imageElement.src;
                const imageCaption = imageElement.alt || '';
                
                galleryImages.push({
                    src: imageSrc,
                    caption: imageCaption
                });
                
                // Open lightbox on click
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    openLightbox(index);
                });
            }
        });
        
        // Open lightbox function
        function openLightbox(index) {
            const lightboxImage = lightbox.querySelector('.lightbox-image');
            const lightboxCaption = lightbox.querySelector('.lightbox-caption');
            
            // Set image and caption
            lightboxImage.src = galleryImages[index].src;
            lightboxCaption.textContent = galleryImages[index].caption;
            
            // Show lightbox with fade
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Set current index for prev/next
            lightbox.dataset.currentIndex = index;
            
            // Update prev/next buttons
            updateLightboxNav();
        }
        
        // Update lightbox navigation
        function updateLightboxNav() {
            const currentIndex = parseInt(lightbox.dataset.currentIndex);
            const prevBtn = lightbox.querySelector('.lightbox-prev');
            const nextBtn = lightbox.querySelector('.lightbox-next');
            
            // Update prev button
            prevBtn.onclick = function() {
                const prevIndex = (currentIndex - 1 + galleryImages.length) % galleryImages.length;
                updateLightboxContent(prevIndex);
            };
            
            // Update next button
            nextBtn.onclick = function() {
                const nextIndex = (currentIndex + 1) % galleryImages.length;
                updateLightboxContent(nextIndex);
            };
        }
        
        // Update lightbox content
        function updateLightboxContent(index) {
            const lightboxImage = lightbox.querySelector('.lightbox-image');
            const lightboxCaption = lightbox.querySelector('.lightbox-caption');
            
            // Fade out current image
            lightboxImage.style.opacity = '0';
            
            setTimeout(function() {
                // Update image and caption
                lightboxImage.src = galleryImages[index].src;
                lightboxCaption.textContent = galleryImages[index].caption;
                
                // Fade in new image
                setTimeout(function() {
                    lightboxImage.style.opacity = '1';
                }, 50);
                
                // Update current index
                lightbox.dataset.currentIndex = index;
            }, 300);
        }
        
        // Close lightbox
        const closeBtn = lightbox.querySelector('.lightbox-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
        
        // Close on overlay click
        lightbox.addEventListener('click', function(e) {
            if (e.target === lightbox) {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (!lightbox.classList.contains('active')) return;
            
            if (e.key === 'Escape') {
                lightbox.classList.remove('active');
                document.body.style.overflow = '';
            } else if (e.key === 'ArrowLeft') {
                lightbox.querySelector('.lightbox-prev').click();
            } else if (e.key === 'ArrowRight') {
                lightbox.querySelector('.lightbox-next').click();
            }
        });
    }
}

/**
 * Ensure equal card heights
 */
function equalizeCardHeights() {
    const cards = document.querySelectorAll('.tour-card');
    if (!cards.length) return;
    
    // Reset heights first
    cards.forEach(card => {
        card.style.height = 'auto';
        // Reset tour image heights too
        const tourImage = card.querySelector('.tour-image');
        if (tourImage && window.innerWidth <= 767) {
            tourImage.style.height = '200px';
        }
    });
    
    // Only equalize on desktop
    if (window.innerWidth >= 768) {
        // Get max card height
        let maxHeight = 0;
        cards.forEach(card => {
            if (card.offsetHeight > maxHeight) {
                maxHeight = card.offsetHeight;
            }
        });
        
        // Apply max height to all cards
        cards.forEach(card => {
            card.style.height = maxHeight + 'px';
        });
    }
    
    // Ensure proper spacing on mobile
    if (window.innerWidth <= 767) {
        cards.forEach(card => {
            card.style.marginBottom = 'var(--spacing-lg)';
        });
    }
}

/**
 * Initialize dropdowns
 */
function dropdownInit() {
    const dropdowns = document.querySelectorAll('.dropdown, .language-dropdown');
    
    dropdowns.forEach(function(dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (toggle && menu) {
            // Önemli değişiklik: Hover değil click ile açılacak
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Tüm diğer açık dropdownları kapat
                dropdowns.forEach(function(otherDropdown) {
                    if (otherDropdown !== dropdown) {
                        const otherMenu = otherDropdown.querySelector('.dropdown-menu');
                        if (otherMenu) {
                            otherMenu.classList.remove('active');
                            otherMenu.style.opacity = '0';
                            otherMenu.style.visibility = 'hidden';
                        }
                    }
                });
                
                // Toggle current dropdown
                if (menu.classList.contains('active')) {
                    menu.classList.remove('active');
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                } else {
                    menu.classList.add('active');
                    menu.style.opacity = '1';
                    menu.style.visibility = 'visible';
                }
            });
            
            // Dropdown dışına tıklandığında kapat
            document.addEventListener('click', function(e) {
                if (!dropdown.contains(e.target)) {
                    menu.classList.remove('active');
                    menu.style.opacity = '0';
                    menu.style.visibility = 'hidden';
                }
            });
        }
    });
}

/**
 * Initialize alerts with auto close
 */
function alertInit() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(function(alert) {
        const closeBtn = alert.querySelector('.alert-close');
        
        if (closeBtn) {
            // Close alert on button click
            closeBtn.addEventListener('click', function() {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.style.display = 'none';
                    
                    // Remove from DOM after animation
                    setTimeout(function() {
                        alert.remove();
                    }, 100);
                }, 300);
            });
            
            // Auto close after 6 seconds
            setTimeout(function() {
                if (document.body.contains(alert)) {
                    alert.style.opacity = '0';
                    setTimeout(function() {
                        alert.style.display = 'none';
                        
                        // Remove from DOM after animation
                        setTimeout(function() {
                            if (document.body.contains(alert)) {
                                alert.remove();
                            }
                        }, 100);
                    }, 300);
                }
            }, 6000);
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
        
        if (tabButtons.length > 0 && tabContents.length > 0) {
            // Initialize with first tab active
            if (!tourTabs.querySelector('.tour-tab-button.active')) {
                tabButtons[0].classList.add('active');
                tabContents[0].classList.add('active');
            }
            
            // Tab click event
            tabButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Get target content ID
                    const target = this.dataset.target;
                    
                    // Deactivate all tabs
                    tabButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });
                    
                    // Hide all contents
                    tabContents.forEach(function(content) {
                        content.classList.remove('active');
                        content.style.opacity = '0';
                    });
                    
                    // Activate this tab
                    this.classList.add('active');
                    
                    // Show target content with fade
                    const targetContent = tourTabs.querySelector(`.tour-tab-content[data-id="${target}"]`);
                    if (targetContent) {
                        targetContent.classList.add('active');
                        setTimeout(function() {
                            targetContent.style.opacity = '1';
                        }, 50);
                    }
                    
                    // Save to localStorage if needed
                    if (this.dataset.saveState) {
                        localStorage.setItem('active-tab', target);
                    }
                });
            });
            
            // Initialize map if exists in tabs
            const mapTab = tourTabs.querySelector('.tour-tab-button[data-target="location"]');
            if (mapTab) {
                mapTab.addEventListener('click', function() {
                    // Initialize map if not already initialized
                    if (typeof google !== 'undefined' && google.maps) {
                        const mapElement = document.getElementById('tour_map');
                        if (mapElement && !mapElement.dataset.initialized) {
                            initTourMap();
                            mapElement.dataset.initialized = 'true';
                        }
                    }
                });
            }
            
            // Restore active tab from localStorage if needed
            if (tourTabs.dataset.saveState) {
                const activeTab = localStorage.getItem('active-tab');
                if (activeTab) {
                    const tab = tourTabs.querySelector(`.tour-tab-button[data-target="${activeTab}"]`);
                    if (tab) {
                        tab.click();
                    }
                }
            }
        }
    }
    
    // Also handle FAQ items (accordion)
    const faqItems = document.querySelectorAll('.faq-item');
    if (faqItems.length > 0) {
        faqItems.forEach(function(item) {
            const question = item.querySelector('.faq-question');
            const answer = item.querySelector('.faq-answer');
            
            if (question && answer) {
                question.addEventListener('click', function() {
                    // Check if already active
                    const isActive = item.classList.contains('active');
                    
                    // Close all other items
                    faqItems.forEach(function(otherItem) {
                        if (otherItem !== item && otherItem.classList.contains('active')) {
                            otherItem.classList.remove('active');
                            const otherAnswer = otherItem.querySelector('.faq-answer');
                            if (otherAnswer) {
                                otherAnswer.style.maxHeight = null;
                            }
                        }
                    });
                    
                    // Toggle current item
                    if (isActive) {
                        item.classList.remove('active');
                        answer.style.maxHeight = null;
                    } else {
                        item.classList.add('active');
                        answer.style.maxHeight = answer.scrollHeight + 'px';
                    }
                });
            }
        });
        
        // Open first item by default
        if (faqItems[0] && !faqItems[0].classList.contains('active')) {
            faqItems[0].querySelector('.faq-question').click();
        }
    }
}

/**
 * Initialize count-up animations for stats
 */
function countUpInit() {
    const stats = document.querySelectorAll('.stat-value[data-count]');
    
    if (stats.length > 0) {
        // Check if element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                rect.right <= (window.innerWidth || document.documentElement.clientWidth)
            );
        }
        
        // Count up function
        function countUp(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16);
            const startTime = performance.now();
            
            function updateCount(currentTime) {
                const elapsed = currentTime - startTime;
                const value = Math.min(start + increment * elapsed, target);
                
                element.textContent = Math.floor(value).toLocaleString();
                
                if (elapsed < duration) {
                    requestAnimationFrame(updateCount);
                } else {
                    element.textContent = target.toLocaleString();
                }
            }
            
            requestAnimationFrame(updateCount);
        }
        
        // Start count when visible
        function checkVisibility() {
            stats.forEach(function(stat) {
                if (isInViewport(stat) && !stat.dataset.counted) {
                    const target = parseInt(stat.dataset.count);
                    countUp(stat, target);
                    stat.dataset.counted = 'true';
                }
            });
        }
        
        // Check on scroll
        window.addEventListener('scroll', checkVisibility);
        
        // Initial check
        checkVisibility();
    }
}

/**
 * Initialize booking form with guest counter and price calculator
 */
function bookingFormInit() {
    const bookingForm = document.querySelector('.booking-widget-form');
    
    if (bookingForm) {
        // Initialize date picker
        const dateInput = bookingForm.querySelector('#booking_date');
        if (dateInput && typeof flatpickr !== 'undefined') {
            flatpickr(dateInput, {
                minDate: 'today',
                dateFormat: 'Y-m-d',
                disableMobile: true
            });
        }
        
        // Initialize guest counter
        const guestCounters = bookingForm.querySelectorAll('.guest-counter');
        guestCounters.forEach(function(counter) {
            const decreaseBtn = counter.querySelector('.decrease-btn');
            const increaseBtn = counter.querySelector('.increase-btn');
            const input = counter.querySelector('input');
            
            if (decreaseBtn && increaseBtn && input) {
                const min = parseInt(input.getAttribute('min') || 0);
                const max = parseInt(input.getAttribute('max') || 10);
                
                // Decrease button
                decreaseBtn.addEventListener('click', function() {
                    let value = parseInt(input.value) || 0;
                    if (value > min) {
                        input.value = value - 1;
                        updateGuestCounter(input);
                        calculateTotal();
                    }
                });
                
                // Increase button
                increaseBtn.addEventListener('click', function() {
                    let value = parseInt(input.value) || 0;
                    if (value < max) {
                        input.value = value + 1;
                        updateGuestCounter(input);
                        calculateTotal();
                    }
                });
                
                // Update buttons state
                function updateGuestCounter(input) {
                    const value = parseInt(input.value) || 0;
                    decreaseBtn.disabled = value <= min;
                    increaseBtn.disabled = value >= max;
                }
                
                // Initial state
                updateGuestCounter(input);
                
                // Input change
                input.addEventListener('change', function() {
                    let value = parseInt(this.value) || 0;
                    
                    // Validate min/max
                    if (value < min) value = min;
                    if (value > max) value = max;
                    
                    this.value = value;
                    updateGuestCounter(this);
                    calculateTotal();
                });
            }
        });
        
        // Calculate total price
        function calculateTotal() {
            const adultsInput = bookingForm.querySelector('#booking_adults');
            const childrenInput = bookingForm.querySelector('#booking_children');
            const priceDisplay = document.querySelector('#price_display');
            const totalDisplay = document.querySelector('#total_price_display');
            const totalInput = bookingForm.querySelector('#booking_total_price');
            
            if (adultsInput && totalDisplay) {
                const adults = parseInt(adultsInput.value) || 0;
                const children = parseInt(childrenInput?.value) || 0;
                
                // Get base price from display or data attribute
                let basePrice = 0;
                const discountPrice = parseFloat(bookingForm.querySelector('#booking_discount_price')?.value) || 0;
                const regularPrice = parseFloat(bookingForm.querySelector('#booking_base_price')?.value) || 0;
                
                // Use discount price if available, otherwise regular price
                basePrice = discountPrice > 0 ? discountPrice : regularPrice;
                
                // Calculate total (adults full price, children half price)
                const total = (adults * basePrice) + (children * basePrice * 0.5);
                
                // Get currency symbol
                const currencySymbol = document.querySelector('#currency_symbol')?.value || '$';
                
                // Update total display
                if (totalDisplay) {
                    totalDisplay.textContent = currencySymbol + total.toFixed(2);
                }
                
                // Update hidden input
                if (totalInput) {
                    totalInput.value = total.toFixed(2);
                }
            }
        }
        
        // Initial calculation
        calculateTotal();
        
        // Form validation
        bookingForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate required fields
            const requiredFields = bookingForm.querySelectorAll('[required]');
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('is-invalid');
                    
                    // Add error message if not exists
                    let errorMsg = field.parentNode.querySelector('.invalid-feedback');
                    if (!errorMsg) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback';
                        errorMsg.textContent = 'This field is required';
                        field.parentNode.appendChild(errorMsg);
                    }
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            // Prevent submission if not valid
            if (!isValid) {
                e.preventDefault();
                
                // Scroll to first error
                const firstError = bookingForm.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }
}

/**
 * Initialize map for tour location
 */
function initTourMap() {
    const mapElement = document.getElementById('tour_map');
    
    if (mapElement && typeof google !== 'undefined' && google.maps) {
        const lat = parseFloat(mapElement.dataset.lat || 38.642335);
        const lng = parseFloat(mapElement.dataset.lng || 34.827335);
        const zoom = parseInt(mapElement.dataset.zoom || 12);
        const title = mapElement.dataset.title || 'Tour Location';
        
        const position = { lat: lat, lng: lng };
        
        // Map options with custom style
        const mapOptions = {
            center: position,
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: true,
            zoomControl: true,
            scrollwheel: false,
            styles: [
                {
                    "featureType": "administrative",
                    "elementType": "labels.text.fill",
                    "stylers": [{ "color": "#444444" }]
                },
                {
                    "featureType": "landscape",
                    "elementType": "all",
                    "stylers": [{ "color": "#f2f2f2" }]
                },
                {
                    "featureType": "poi",
                    "elementType": "all",
                    "stylers": [{ "visibility": "off" }]
                },
                {
                    "featureType": "road",
                    "elementType": "all",
                    "stylers": [
                        { "saturation": -100 },
                        { "lightness": 45 }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "all",
                    "stylers": [{ "visibility": "simplified" }]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "labels.icon",
                    "stylers": [{ "visibility": "off" }]
                },
                {
                    "featureType": "transit",
                    "elementType": "all",
                    "stylers": [{ "visibility": "off" }]
                },
                {
                    "featureType": "water",
                    "elementType": "all",
                    "stylers": [
                        { "color": "#46bcec" },
                        { "visibility": "on" }
                    ]
                }
            ]
        };
        
        // Create map
        const map = new google.maps.Map(mapElement, mapOptions);
        
        // Add marker
        const marker = new google.maps.Marker({
            position: position,
            map: map,
            title: title,
            animation: google.maps.Animation.DROP
        });
        
        // Add info window if title is provided
        if (title) {
            const infoWindow = new google.maps.InfoWindow({
                content: `<div class="map-info-window"><h4>${title}</h4></div>`
            });
            
            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
            
            // Open info window by default
            infoWindow.open(map, marker);
        }
    }
}