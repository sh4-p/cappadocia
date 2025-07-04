/**
 * Tour Gallery Lightbox - Mobil Uyumlu
 * Advanced gallery lightbox for tour detail pages
 */

class TourGalleryLightbox {
    constructor() {
        this.lightbox = document.getElementById('galleryLightbox');
        this.lightboxImage = document.getElementById('lightboxImage');
        this.lightboxTitle = document.getElementById('lightboxTitle');
        this.lightboxCurrent = document.getElementById('lightboxCurrent');
        this.lightboxTotal = document.getElementById('lightboxTotal');
        this.lightboxLoading = document.getElementById('lightboxLoading');
        
        this.currentIndex = 0;
        this.images = [];
        this.zoomLevel = 1;
        this.isZoomed = false;
        this.startX = 0;
        this.startY = 0;
        this.translateX = 0;
        this.translateY = 0;
        this.isDragging = false;
        this.isLoading = false;
        
        this.init();
    }

    init() {
        if (!this.lightbox) {
            console.warn('Lightbox element not found');
            return;
        }
        
        this.collectImages();
        this.bindEvents();
        
        // Initialize on DOM ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.collectImages();
            });
        }
    }

    collectImages() {
        this.images = [];
        
        // Ana tur resmi
        const heroImage = document.querySelector('.tour-hero');
        if (heroImage) {
            const bgImage = heroImage.style.backgroundImage;
            const match = bgImage.match(/url\(['"]?([^'")]+)['"]?\)/);
            if (match) {
                this.images.push({
                    src: match[1],
                    alt: document.querySelector('.tour-title')?.textContent || 'Tour Image',
                    title: document.querySelector('.tour-title')?.textContent || 'Tour Image'
                });
            }
        }
        
        // Swiper galeri resimleri
        const swiperSlides = document.querySelectorAll('.gallery-lightbox-item img');
        swiperSlides.forEach((img, index) => {
            // Ana resmi tekrar eklemeyelim
            if (index === 0 && this.images.length > 0) return;
            
            this.images.push({
                src: img.src,
                alt: img.alt || 'Gallery Image',
                title: img.alt || 'Gallery Image'
            });
        });

        // Grid galeri resimleri
        const gridItems = document.querySelectorAll('.gallery-grid-item img');
        gridItems.forEach((img) => {
            // Duplicate check
            if (!this.images.find(image => image.src === img.src)) {
                this.images.push({
                    src: img.src,
                    alt: img.alt || 'Gallery Image',
                    title: img.alt || 'Gallery Image'
                });
            }
        });

        // Update total count
        if (this.lightboxTotal) {
            this.lightboxTotal.textContent = this.images.length;
        }
        
        console.log('Collected images:', this.images.length);
    }

    bindEvents() {
        if (!this.lightbox) return;
        
        // Click events for gallery items
        document.addEventListener('click', (e) => {
            const galleryItem = e.target.closest('.gallery-lightbox-item, .gallery-grid-item');
            if (galleryItem) {
                e.preventDefault();
                const index = parseInt(galleryItem.dataset.lightboxIndex) || 0;
                this.openLightbox(index);
            }
        });

        // Gallery expand button
        const expandBtn = document.querySelector('.gallery-expand-btn');
        if (expandBtn) {
            expandBtn.addEventListener('click', () => this.openLightbox(0));
        }

        // Lightbox controls
        const closeBtn = document.getElementById('lightboxClose');
        const prevBtn = document.getElementById('lightboxPrev');
        const nextBtn = document.getElementById('lightboxNext');
        
        if (closeBtn) closeBtn.addEventListener('click', () => this.closeLightbox());
        if (prevBtn) prevBtn.addEventListener('click', () => this.previousImage());
        if (nextBtn) nextBtn.addEventListener('click', () => this.nextImage());

        // Touch areas for mobile
        const touchPrev = document.getElementById('lightboxTouchPrev');
        const touchNext = document.getElementById('lightboxTouchNext');
        
        if (touchPrev) touchPrev.addEventListener('click', () => this.previousImage());
        if (touchNext) touchNext.addEventListener('click', () => this.nextImage());

        // Zoom controls
        const zoomIn = document.getElementById('zoomIn');
        const zoomOut = document.getElementById('zoomOut');
        const zoomReset = document.getElementById('zoomReset');
        
        if (zoomIn) zoomIn.addEventListener('click', () => this.zoomIn());
        if (zoomOut) zoomOut.addEventListener('click', () => this.zoomOut());
        if (zoomReset) zoomReset.addEventListener('click', () => this.resetZoom());

        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));

        // Close on backdrop click
        this.lightbox.addEventListener('click', (e) => {
            if (e.target === this.lightbox) {
                this.closeLightbox();
            }
        });

        // Touch events for mobile gestures
        this.bindTouchEvents();

        // Mouse events for zoom and pan
        this.bindMouseEvents();
    }

    bindTouchEvents() {
        if (!this.lightboxImage) return;
        
        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let touchEndY = 0;
        let initialDistance = 0;
        let lastTouchTime = 0;

        this.lightboxImage.addEventListener('touchstart', (e) => {
            const now = Date.now();
            
            if (e.touches.length === 1) {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
                
                // Double tap to zoom
                if (now - lastTouchTime < 300) {
                    e.preventDefault();
                    if (this.isZoomed) {
                        this.resetZoom();
                    } else {
                        this.zoomIn();
                    }
                }
                lastTouchTime = now;
            } else if (e.touches.length === 2) {
                initialDistance = this.getDistance(e.touches[0], e.touches[1]);
            }
        }, { passive: false });

        this.lightboxImage.addEventListener('touchmove', (e) => {
            if (e.touches.length === 1 && this.isZoomed) {
                e.preventDefault();
                const touch = e.touches[0];
                const deltaX = touch.clientX - touchStartX;
                const deltaY = touch.clientY - touchStartY;
                
                this.translateX += deltaX;
                this.translateY += deltaY;
                
                this.updateImageTransform();
                
                touchStartX = touch.clientX;
                touchStartY = touch.clientY;
            } else if (e.touches.length === 2) {
                e.preventDefault();
                const currentDistance = this.getDistance(e.touches[0], e.touches[1]);
                const scale = currentDistance / initialDistance;
                
                this.zoomLevel = Math.min(Math.max(scale, 1), 4);
                this.isZoomed = this.zoomLevel > 1;
                this.updateImageTransform();
            }
        }, { passive: false });

        this.lightboxImage.addEventListener('touchend', (e) => {
            if (e.changedTouches.length === 1) {
                touchEndX = e.changedTouches[0].clientX;
                touchEndY = e.changedTouches[0].clientY;
                
                const deltaX = touchEndX - touchStartX;
                const deltaY = touchEndY - touchStartY;
                
                // Swipe detection (only when not zoomed)
                if (!this.isZoomed && Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                    if (deltaX > 0) {
                        this.previousImage();
                    } else {
                        this.nextImage();
                    }
                }
            }
        }, { passive: true });
    }

    bindMouseEvents() {
        if (!this.lightboxImage) return;
        
        // Mouse wheel zoom
        this.lightboxImage.addEventListener('wheel', (e) => {
            e.preventDefault();
            
            if (e.deltaY < 0) {
                this.zoomIn();
            } else {
                this.zoomOut();
            }
        }, { passive: false });

        // Mouse drag for panning
        this.lightboxImage.addEventListener('mousedown', (e) => {
            if (this.isZoomed) {
                this.isDragging = true;
                this.startX = e.clientX - this.translateX;
                this.startY = e.clientY - this.translateY;
                this.lightboxImage.style.cursor = 'grabbing';
            }
        });

        document.addEventListener('mousemove', (e) => {
            if (this.isDragging && this.isZoomed) {
                this.translateX = e.clientX - this.startX;
                this.translateY = e.clientY - this.startY;
                this.updateImageTransform();
            }
        });

        document.addEventListener('mouseup', () => {
            this.isDragging = false;
            if (this.isZoomed) {
                this.lightboxImage.style.cursor = 'grab';
            } else {
                this.lightboxImage.style.cursor = 'default';
            }
        });
    }

    getDistance(touch1, touch2) {
        const dx = touch1.clientX - touch2.clientX;
        const dy = touch1.clientY - touch2.clientY;
        return Math.sqrt(dx * dx + dy * dy);
    }

    openLightbox(index = 0) {
        if (!this.images.length) {
            console.warn('No images found for lightbox');
            return;
        }
        
        this.currentIndex = Math.max(0, Math.min(index, this.images.length - 1));
        this.showImage();
        this.lightbox.classList.add('active');
        this.lightbox.setAttribute('aria-hidden', 'false');
        document.body.classList.add('lightbox-open');
        
        // Focus management
        const closeBtn = document.getElementById('lightboxClose');
        if (closeBtn) {
            setTimeout(() => closeBtn.focus(), 100);
        }
    }

    closeLightbox() {
        this.lightbox.classList.remove('active');
        this.lightbox.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('lightbox-open');
        this.resetZoom();
    }

    showImage() {
        if (!this.images[this.currentIndex]) return;
        
        const image = this.images[this.currentIndex];
        
        // Show loading
        if (this.lightboxLoading) {
            this.lightboxLoading.style.display = 'block';
        }
        if (this.lightboxImage) {
            this.lightboxImage.style.opacity = '0';
        }
        
        this.isLoading = true;
        
        // Update info
        if (this.lightboxTitle) {
            this.lightboxTitle.textContent = image.title;
        }
        if (this.lightboxCurrent) {
            this.lightboxCurrent.textContent = this.currentIndex + 1;
        }
        
        // Load new image
        const img = new Image();
        img.onload = () => {
            if (this.lightboxImage) {
                this.lightboxImage.src = img.src;
                this.lightboxImage.alt = image.alt;
                this.lightboxImage.style.opacity = '1';
            }
            if (this.lightboxLoading) {
                this.lightboxLoading.style.display = 'none';
            }
            this.isLoading = false;
        };
        
        img.onerror = () => {
            if (this.lightboxLoading) {
                this.lightboxLoading.style.display = 'none';
            }
            this.isLoading = false;
            console.error('Error loading image:', image.src);
        };
        
        img.src = image.src;
    }

    previousImage() {
        if (this.isLoading) return;
        
        this.resetZoom();
        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
        this.showImage();
    }

    nextImage() {
        if (this.isLoading) return;
        
        this.resetZoom();
        this.currentIndex = (this.currentIndex + 1) % this.images.length;
        this.showImage();
    }

    zoomIn() {
        this.zoomLevel = Math.min(this.zoomLevel * 1.5, 4);
        this.isZoomed = this.zoomLevel > 1;
        this.updateImageTransform();
    }

    zoomOut() {
        this.zoomLevel = Math.max(this.zoomLevel / 1.5, 1);
        this.isZoomed = this.zoomLevel > 1;
        if (!this.isZoomed) {
            this.translateX = 0;
            this.translateY = 0;
        }
        this.updateImageTransform();
    }

    resetZoom() {
        this.zoomLevel = 1;
        this.isZoomed = false;
        this.translateX = 0;
        this.translateY = 0;
        this.updateImageTransform();
    }

    updateImageTransform() {
        if (!this.lightboxImage) return;
        
        const transform = `scale(${this.zoomLevel}) translate(${this.translateX / this.zoomLevel}px, ${this.translateY / this.zoomLevel}px)`;
        this.lightboxImage.style.transform = transform;
        
        if (this.isZoomed) {
            this.lightboxImage.style.cursor = 'grab';
        } else {
            this.lightboxImage.style.cursor = 'default';
        }
    }

    handleKeyboard(e) {
        if (!this.lightbox.classList.contains('active')) return;

        switch (e.key) {
            case 'Escape':
                e.preventDefault();
                this.closeLightbox();
                break;
            case 'ArrowLeft':
                e.preventDefault();
                this.previousImage();
                break;
            case 'ArrowRight':
                e.preventDefault();
                this.nextImage();
                break;
            case '+':
            case '=':
                e.preventDefault();
                this.zoomIn();
                break;
            case '-':
                e.preventDefault();
                this.zoomOut();
                break;
            case '0':
                e.preventDefault();
                this.resetZoom();
                break;
        }
    }

    // Public method to open lightbox from external code
    open(index = 0) {
        this.openLightbox(index);
    }
    
    // Public method to close lightbox from external code
    close() {
        this.closeLightbox();
    }
    
    // Public method to go to specific image
    goToImage(index) {
        if (index >= 0 && index < this.images.length) {
            this.currentIndex = index;
            this.showImage();
        }
    }
}

// Global function for opening lightbox (for backward compatibility)
function openGalleryLightbox(index = 0) {
    if (window.tourLightbox) {
        window.tourLightbox.open(index);
    }
}

// Global function for closing lightbox
function closeGalleryLightbox() {
    if (window.tourLightbox) {
        window.tourLightbox.close();
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on a tour detail page
    if (document.querySelector('.tour-gallery') || document.querySelector('#galleryLightbox')) {
        window.tourLightbox = new TourGalleryLightbox();
        console.log('Tour Gallery Lightbox initialized');
    }
});

// Re-initialize after dynamic content changes
function reinitializeLightbox() {
    if (window.tourLightbox) {
        window.tourLightbox.collectImages();
        console.log('Lightbox reinitialized');
    }
}

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TourGalleryLightbox;
}