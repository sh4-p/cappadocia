/**
 * Tours JavaScript
 * 
 * Handles tour listing and detail functionality
 */

// Wait for the DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    'use strict';
    
    // Initialize tour components
    initTourFilters();
    initTourSearch();
    initLoadMore();
    initTourSlider();
    initTourTabs();
    initTourGallery();
    initBookingWidget();
    initTourMap();
    initShareButtons();
});

/**
 * Initialize tour filters
 */
function initTourFilters() {
    const filterForm = document.querySelector('.tour-filters form');
    const filterToggles = document.querySelectorAll('.filter-toggle');
    const filterReset = document.querySelector('.filter-reset');
    const filterGroups = document.querySelectorAll('.filter-group');
    const filterMobileToggle = document.querySelector('.filter-mobile-toggle');
    const filterContainer = document.querySelector('.tour-filters');
    
    // Toggle filter groups
    if (filterToggles.length > 0) {
        filterToggles.forEach(function(toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                const group = this.closest('.filter-group');
                if (group) {
                    group.classList.toggle('open');
                }
            });
        });
    }
    
    // Reset filters
    if (filterReset) {
        filterReset.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Reset all filter inputs
            const inputs = filterForm.querySelectorAll('input:not([type="hidden"]), select');
            inputs.forEach(function(input) {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    input.checked = false;
                } else {
                    input.value = '';
                }
            });
            
            // Reset price range slider
            const priceRange = filterForm.querySelector('.price-range-slider');
            if (priceRange && window.noUiSlider && priceRange.noUiSlider) {
                const min = parseInt(priceRange.dataset.min || 0);
                const max = parseInt(priceRange.dataset.max || 1000);
                priceRange.noUiSlider.set([min, max]);
            }
            
            // Submit form
            filterForm.submit();
        });
    }
    
    // Mobile filter toggle
    if (filterMobileToggle && filterContainer) {
        filterMobileToggle.addEventListener('click', function(e) {
            e.preventDefault();
            filterContainer.classList.toggle('filters-open');
            document.body.classList.toggle('filters-opened');
        });
        
        // Close filters when clicking outside
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 992 && 
                filterContainer.classList.contains('filters-open') && 
                !filterContainer.contains(e.target) && 
                !filterMobileToggle.contains(e.target)) {
                filterContainer.classList.remove('filters-open');
                document.body.classList.remove('filters-opened');
            }
        });
    }
    
    // Price range slider
    const priceRange = document.querySelector('.price-range-slider');
    if (priceRange && window.noUiSlider) {
        const min = parseInt(priceRange.dataset.min || 0);
        const max = parseInt(priceRange.dataset.max || 1000);
        const currency = priceRange.dataset.currency || '€';
        
        noUiSlider.create(priceRange, {
            start: [min, max],
            connect: true,
            step: 10,
            range: {
                'min': min,
                'max': max
            },
            format: {
                to: function(value) {
                    return Math.round(value);
                },
                from: function(value) {
                    return Number(value);
                }
            }
        });
        
        // Update price range inputs
        const minInput = document.querySelector('#price_min');
        const maxInput = document.querySelector('#price_max');
        const minDisplay = document.querySelector('.price-min-display');
        const maxDisplay = document.querySelector('.price-max-display');
        
        if (minInput && maxInput) {
            priceRange.noUiSlider.on('update', function(values, handle) {
                if (handle === 0) {
                    minInput.value = values[0];
                    if (minDisplay) {
                        minDisplay.textContent = currency + values[0];
                    }
                } else {
                    maxInput.value = values[1];
                    if (maxDisplay) {
                        maxDisplay.textContent = currency + values[1];
                    }
                }
            });
        }
        
        // Submit form on change with delay
        priceRange.noUiSlider.on('change', function() {
            if (filterForm.dataset.autoSubmit === 'true') {
                const timer = setTimeout(function() {
                    filterForm.submit();
                }, 500);
            }
        });
    }
    
    // Auto-submit on filter change
    if (filterForm && filterForm.dataset.autoSubmit === 'true') {
        const inputs = filterForm.querySelectorAll('input:not([type="hidden"]):not([type="text"]), select');
        inputs.forEach(function(input) {
            input.addEventListener('change', function() {
                filterForm.submit();
            });
        });
    }
}

/**
 * Initialize tour search
 */
function initTourSearch() {
    const searchForm = document.querySelector('.tour-search-form');
    const searchInput = document.querySelector('.tour-search-input');
    const searchResults = document.querySelector('.tour-search-results');
    
    if (searchForm && searchInput && searchResults) {
        let searchTimeout;
        
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            const langCode = document.documentElement.lang || 'en';
            
            clearTimeout(searchTimeout);
            
            if (query.length < 2) {
                searchResults.innerHTML = '';
                searchResults.classList.remove('show');
                return;
            }
            
            searchTimeout = setTimeout(function() {
                // Show loading indicator
                searchResults.innerHTML = '<div class="search-loading"><div class="spinner"></div></div>';
                searchResults.classList.add('show');
                
                // Make AJAX request
                fetch(`${window.location.origin}/${langCode}/tours/ajax-search?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            // Build results HTML
                            let html = '';
                            
                            data.forEach(function(tour) {
                                html += `
                                    <div class="search-result-item">
                                        <a href="${tour.url}">
                                            <div class="search-result-image">
                                                <img src="${window.location.origin}/public/uploads/tours/${tour.image}" alt="${tour.name}">
                                            </div>
                                            <div class="search-result-content">
                                                <h4>${tour.name}</h4>
                                                <div class="search-result-price">
                                                    ${tour.discount_price ? `<del>${tour.price}</del> ${tour.discount_price}` : tour.price}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                `;
                            });
                            
                            searchResults.innerHTML = html;
                        } else {
                            searchResults.innerHTML = '<div class="search-no-results">No tours found. Try a different search.</div>';
                        }
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = '<div class="search-error">Error loading results. Please try again.</div>';
                    });
            }, 300);
        });
        
        // Close search results when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchForm.contains(e.target)) {
                searchResults.classList.remove('show');
            }
        });
        
        // Submit form on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    }
}

/**
 * Initialize load more functionality
 */
function initLoadMore() {
    const loadMoreBtn = document.querySelector('.load-more-btn');
    const toursGrid = document.querySelector('.tours-grid');
    
    if (loadMoreBtn && toursGrid) {
        loadMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const url = this.getAttribute('href');
            const langCode = document.documentElement.lang || 'en';
            
            // Show loading state
            loadMoreBtn.classList.add('loading');
            loadMoreBtn.querySelector('span').textContent = loadMoreBtn.dataset.loadingText || 'Loading...';
            
            // Make AJAX request
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Append new tours
                        toursGrid.insertAdjacentHTML('beforeend', data.html);
                        
                        // Update load more button
                        if (data.count < data.limit) {
                            loadMoreBtn.style.display = 'none';
                        } else {
                            // Update page number in URL
                            const nextPage = parseInt(loadMoreBtn.dataset.page) + 1;
                            loadMoreBtn.dataset.page = nextPage;
                            
                            const urlObj = new URL(url);
                            urlObj.searchParams.set('page', nextPage);
                            loadMoreBtn.setAttribute('href', urlObj.toString());
                        }
                        
                        // Initialize AOS for new elements
                        if (typeof AOS !== 'undefined') {
                            AOS.refresh();
                        }
                    } else {
                        loadMoreBtn.style.display = 'none';
                    }
                })
                .catch(error => {
                    console.error('Load more error:', error);
                })
                .finally(() => {
                    // Reset loading state
                    loadMoreBtn.classList.remove('loading');
                    loadMoreBtn.querySelector('span').textContent = loadMoreBtn.dataset.text || 'Load More';
                });
        });
    }
}

/**
 * Initialize tour slider
 */
function initTourSlider() {
    const tourSlider = document.querySelector('.tour-slider');
    
    if (tourSlider) {
        const slider = tourSlider.querySelector('.slider');
        const prevBtn = tourSlider.querySelector('.slider-prev');
        const nextBtn = tourSlider.querySelector('.slider-next');
        const slides = tourSlider.querySelectorAll('.slide');
        
        if (slides.length > 0) {
            let currentSlide = 0;
            const totalSlides = slides.length;
            
            // Show slide function
            const showSlide = function(index) {
                // Hide all slides
                slides.forEach(function(slide) {
                    slide.style.display = 'none';
                });
                
                // Show current slide
                slides[index].style.display = 'block';
                
                // Update current slide index
                currentSlide = index;
            };
            
            // Show first slide
            showSlide(currentSlide);
            
            // Previous slide button
            if (prevBtn) {
                prevBtn.addEventListener('click', function() {
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                    showSlide(currentSlide);
                });
            }
            
            // Next slide button
            if (nextBtn) {
                nextBtn.addEventListener('click', function() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showSlide(currentSlide);
                });
            }
            
            // Auto slide
            let autoSlide;
            const startAutoSlide = function() {
                autoSlide = setInterval(function() {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showSlide(currentSlide);
                }, 5000);
            };
            
            const stopAutoSlide = function() {
                clearInterval(autoSlide);
            };
            
            // Start auto slide
            startAutoSlide();
            
            // Pause on hover
            tourSlider.addEventListener('mouseenter', stopAutoSlide);
            tourSlider.addEventListener('mouseleave', startAutoSlide);
            
            // Touch events
            let touchStartX = 0;
            let touchEndX = 0;
            
            tourSlider.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, { passive: true });
            
            tourSlider.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, { passive: true });
            
            const handleSwipe = function() {
                if (touchEndX < touchStartX) {
                    // Swipe left
                    currentSlide = (currentSlide + 1) % totalSlides;
                } else if (touchEndX > touchStartX) {
                    // Swipe right
                    currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                }
                showSlide(currentSlide);
            };
        }
    }
}

/**
 * Initialize tour tabs
 */
function initTourTabs() {
    const tourTabs = document.querySelector('.tour-tabs');
    
    if (tourTabs) {
        const tabButtons = tourTabs.querySelectorAll('.tour-tab-button');
        const tabContents = tourTabs.querySelectorAll('.tour-tab-content');
        
        if (tabButtons.length > 0 && tabContents.length > 0) {
            tabButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Remove active class from all buttons and contents
                    tabButtons.forEach(function(btn) {
                        btn.classList.remove('active');
                    });
                    
                    tabContents.forEach(function(content) {
                        content.classList.remove('active');
                    });
                    
                    // Add active class to current button
                    this.classList.add('active');
                    
                    // Show corresponding content
                    const target = this.dataset.target;
                    const content = tourTabs.querySelector(`.tour-tab-content[data-id="${target}"]`);
                    
                    if (content) {
                        content.classList.add('active');
                    }
                });
            });
            
            // Set first tab as active by default
            if (tabButtons[0]) {
                tabButtons[0].click();
            }
        }
    }
}

/**
 * Initialize tour gallery
 */
function initTourGallery() {
    const galleryItems = document.querySelectorAll('.tour-gallery-item');
    
    if (galleryItems.length > 0) {
        galleryItems.forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                const imageUrl = this.getAttribute('href');
                const imageTitle = this.getAttribute('title') || '';
                
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
                    
                    // Close lightbox when clicking close button or outside image
                    lightbox.querySelector('.lightbox-close').addEventListener('click', function() {
                        lightbox.classList.remove('active');
                    });
                    
                    lightbox.addEventListener('click', function(e) {
                        if (e.target === lightbox) {
                            lightbox.classList.remove('active');
                        }
                    });
                    
                    // Keyboard navigation
                    document.addEventListener('keydown', function(e) {
                        if (!lightbox.classList.contains('active')) {
                            return;
                        }
                        
                        if (e.key === 'Escape') {
                            lightbox.classList.remove('active');
                        } else if (e.key === 'ArrowLeft') {
                            lightbox.querySelector('.lightbox-prev').click();
                        } else if (e.key === 'ArrowRight') {
                            lightbox.querySelector('.lightbox-next').click();
                        }
                    });
                }
                
                // Set current image and caption
                const lightboxImage = lightbox.querySelector('.lightbox-image');
                const lightboxCaption = lightbox.querySelector('.lightbox-caption');
                
                lightboxImage.src = imageUrl;
                lightboxCaption.textContent = imageTitle;
                
                // Show lightbox
                lightbox.classList.add('active');
                
                // Set prev/next buttons
                const prevButton = lightbox.querySelector('.lightbox-prev');
                const nextButton = lightbox.querySelector('.lightbox-next');
                
                // Get all gallery items
                const allGalleryItems = Array.from(document.querySelectorAll('.tour-gallery-item'));
                const currentIndex = allGalleryItems.indexOf(this);
                
                // Previous button
                prevButton.addEventListener('click', function() {
                    const prevIndex = (currentIndex - 1 + allGalleryItems.length) % allGalleryItems.length;
                    const prevItem = allGalleryItems[prevIndex];
                    
                    lightboxImage.src = prevItem.getAttribute('href');
                    lightboxCaption.textContent = prevItem.getAttribute('title') || '';
                });
                
                // Next button
                nextButton.addEventListener('click', function() {
                    const nextIndex = (currentIndex + 1) % allGalleryItems.length;
                    const nextItem = allGalleryItems[nextIndex];
                    
                    lightboxImage.src = nextItem.getAttribute('href');
                    lightboxCaption.textContent = nextItem.getAttribute('title') || '';
                });
            });
        });
    }
}

/**
 * Initialize booking widget
 */
function initBookingWidget() {
    const bookingWidget = document.querySelector('.tour-booking-widget');
    
    if (bookingWidget) {
        // Make booking widget sticky
        const sidebar = document.querySelector('.tour-sidebar');
        const content = document.querySelector('.tour-content');
        
        if (sidebar && content && window.innerWidth >= 992) {
            const sidebarTop = sidebar.offsetTop;
            const contentBottom = content.offsetTop + content.offsetHeight;
            
            window.addEventListener('scroll', function() {
                const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                const sidebarHeight = sidebar.offsetHeight;
                
                if (scrollTop > sidebarTop && scrollTop + sidebarHeight < contentBottom) {
                    sidebar.classList.add('sticky');
                    sidebar.classList.remove('sticky-bottom');
                } else if (scrollTop + sidebarHeight >= contentBottom) {
                    sidebar.classList.remove('sticky');
                    sidebar.classList.add('sticky-bottom');
                } else {
                    sidebar.classList.remove('sticky');
                    sidebar.classList.remove('sticky-bottom');
                }
            });
        }
    }
}

/**
 * Initialize tour map
 */
function initTourMap() {
    const tourMap = document.querySelector('#tour_map');
    
    if (tourMap && window.google && window.google.maps) {
        const lat = parseFloat(tourMap.dataset.lat || 0);
        const lng = parseFloat(tourMap.dataset.lng || 0);
        const zoom = parseInt(tourMap.dataset.zoom || 13);
        const title = tourMap.dataset.title || '';
        
        // Create map
        const map = new google.maps.Map(tourMap, {
            center: { lat: lat, lng: lng },
            zoom: zoom,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            scrollwheel: false
        });
        
        // Add marker
        const marker = new google.maps.Marker({
            position: { lat: lat, lng: lng },
            map: map,
            title: title
        });
        
        // Add info window
        if (title) {
            const infoWindow = new google.maps.InfoWindow({
                content: `<div class="map-info-window"><h4>${title}</h4></div>`
            });
            
            marker.addListener('click', function() {
                infoWindow.open(map, marker);
            });
        }
    }
}

/**
 * Initialize share buttons
 */
function initShareButtons() {
    const shareButtons = document.querySelectorAll('.share-button');
    
    if (shareButtons.length > 0) {
        shareButtons.forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const type = this.dataset.type;
                const url = encodeURIComponent(window.location.href);
                const title = encodeURIComponent(document.title);
                let shareUrl;
                
                // Create share URL based on type
                switch (type) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
                        break;
                    case 'pinterest':
                        const image = this.dataset.image ? encodeURIComponent(this.dataset.image) : '';
                        shareUrl = `https://pinterest.com/pin/create/button/?url=${url}&media=${image}&description=${title}`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://api.whatsapp.com/send?text=${title}%20${url}`;
                        break;
                    case 'email':
                        shareUrl = `mailto:?subject=${title}&body=${url}`;
                        break;
                }
                
                // Open share window
                if (shareUrl) {
                    window.open(shareUrl, 'share-window', 'height=450, width=550, toolbar=0, menubar=0, directories=0, scrollbars=0');
                }
            });
        });
    }
}