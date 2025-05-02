<?php
/**
 * Gallery index view
 */
?>

<!-- Page Header -->
<section class="page-header" style="background-image: url('<?php echo $imgUrl; ?>/gallery-header.jpg');">
    <div class="container">
        <div class="page-header-content">
            <h1 class="page-title"><?php echo $pageTitle; ?></h1>
            
            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <ul class="breadcrumbs-list">
                    <li class="breadcrumbs-item"><a href="<?php echo $appUrl . '/' . $currentLang; ?>"><?php _e('home'); ?></a></li>
                    <li class="breadcrumbs-item active"><?php _e('gallery'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section class="gallery-section section">
    <div class="container">
        <?php if (!empty($categories)): ?>
        <!-- Gallery Filter -->
        <div class="gallery-filter">
            <ul class="filter-categories">
                <li class="filter-category <?php echo empty($filter) ? 'active' : ''; ?>">
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery"><?php _e('all'); ?></a>
                </li>
                <?php foreach ($categories as $category): ?>
                <li class="filter-category <?php echo isset($filter) && $filter === $category['slug'] ? 'active' : ''; ?>">
                    <a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery?filter=<?php echo $category['slug']; ?>"><?php echo $category['name']; ?></a>
                </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($galleryItems)): ?>
        <!-- Gallery Grid -->
        <div class="gallery-grid">
            <?php foreach ($galleryItems as $index => $item): ?>
            <div class="gallery-item" data-aos="fade-up" data-aos-delay="<?php echo ($index % 8) * 100; ?>">
                <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo $item['title'] ?: __('gallery_image'); ?>" class="gallery-image">
                <div class="gallery-overlay">
                    <a href="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" class="gallery-link" title="<?php echo $item['title']; ?>">
                        <i class="material-icons gallery-icon">zoom_in</i>
                    </a>
                    <?php if ($item['tour_id']): ?>
                    <a href="<?php echo $appUrl . '/' . $currentLang . '/tours/' . $item['tour_slug']; ?>" class="gallery-tour-link">
                        <i class="material-icons">explore</i>
                        <span><?php echo $item['tour_name']; ?></span>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($currentPage > 1): ?>
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery<?php echo !empty($filter) ? '?filter=' . $filter . '&' : '?'; ?>page=<?php echo $currentPage - 1; ?>" class="pagination-item">
                <i class="material-icons">chevron_left</i>
            </a>
            <?php endif; ?>
            
            <?php
            $startPage = max(1, $currentPage - 2);
            $endPage = min($totalPages, $startPage + 4);
            
            if ($endPage - $startPage < 4) {
                $startPage = max(1, $endPage - 4);
            }
            
            for ($i = $startPage; $i <= $endPage; $i++):
            ?>
                <a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery<?php echo !empty($filter) ? '?filter=' . $filter . '&' : '?'; ?>page=<?php echo $i; ?>" class="pagination-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            
            <?php if ($currentPage < $totalPages): ?>
            <a href="<?php echo $appUrl . '/' . $currentLang; ?>/gallery<?php echo !empty($filter) ? '?filter=' . $filter . '&' : '?'; ?>page=<?php echo $currentPage + 1; ?>" class="pagination-item">
                <i class="material-icons">chevron_right</i>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php else: ?>
        <!-- No Gallery Items -->
        <div class="no-items">
            <div class="no-items-icon">
                <i class="material-icons">photo_library</i>
            </div>
            <h3 class="no-items-title"><?php _e('no_gallery_items_found'); ?></h3>
            <p class="no-items-text"><?php _e('no_gallery_items_text'); ?></p>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- Custom CSS for gallery view -->
<style>
    /* Gallery Filter */
    .gallery-filter {
        margin-bottom: var(--spacing-xl);
    }
    
    .filter-categories {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        justify-content: center;
    }
    
    .filter-category a {
        display: block;
        padding: 0.5rem 1.5rem;
        border-radius: var(--border-radius-md);
        font-weight: var(--font-weight-medium);
        color: var(--dark-color);
        background-color: var(--gray-200);
        transition: all var(--transition-fast);
    }
    
    .filter-category.active a,
    .filter-category a:hover {
        background-color: var(--primary-color);
        color: var(--white-color);
    }
    
    /* Gallery Grid */
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
        margin-bottom: var(--spacing-xl);
    }
    
    .gallery-item {
        position: relative;
        overflow: hidden;
        border-radius: var(--border-radius-md);
        aspect-ratio: 1;
    }
    
    .gallery-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform var(--transition-medium);
    }
    
    .gallery-item:hover .gallery-image {
        transform: scale(1.1);
    }
    
    .gallery-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity var(--transition-medium);
    }
    
    .gallery-item:hover .gallery-overlay {
        opacity: 1;
    }
    
    .gallery-link {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--white-color);
        font-size: 2rem;
        margin-bottom: 1rem;
        transition: background-color var(--transition-fast);
    }
    
    .gallery-link:hover {
        background-color: var(--primary-color);
        color: var(--white-color);
    }
    
    .gallery-tour-link {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        background-color: rgba(255, 255, 255, 0.2);
        color: var(--white-color);
        font-size: 0.9rem;
        transition: background-color var(--transition-fast);
    }
    
    .gallery-tour-link:hover {
        background-color: var(--primary-color);
        color: var(--white-color);
    }
    
    /* No Items */
    .no-items {
        text-align: center;
        padding: var(--spacing-xxl) 0;
    }
    
    .no-items-icon {
        font-size: 5rem;
        color: var(--gray-300);
        margin-bottom: var(--spacing-md);
    }
    
    .no-items-icon i {
        font-size: inherit;
    }
    
    .no-items-title {
        font-size: var(--font-size-xl);
        margin-bottom: var(--spacing-md);
    }
    
    .no-items-text {
        color: var(--gray-600);
        max-width: 500px;
        margin: 0 auto;
    }
    
    /* Gallery Lightbox */
    .gallery-lightbox {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: var(--z-index-modal);
        display: none;
        opacity: 0;
        transition: opacity var(--transition-medium);
    }
    
    .gallery-lightbox.active {
        display: flex;
        opacity: 1;
    }
    
    .lightbox-content {
        position: relative;
        max-width: 80%;
        max-height: 80%;
        margin: auto;
    }
    
    .lightbox-image {
        max-width: 100%;
        max-height: 80vh;
        display: block;
        margin: 0 auto;
    }
    
    .lightbox-caption {
        color: var(--white-color);
        text-align: center;
        padding: var(--spacing-md) 0;
    }
    
    .lightbox-close,
    .lightbox-prev,
    .lightbox-next {
        position: absolute;
        background: none;
        border: none;
        color: var(--white-color);
        font-size: 2rem;
        cursor: pointer;
        transition: color var(--transition-fast);
    }
    
    .lightbox-close:hover,
    .lightbox-prev:hover,
    .lightbox-next:hover {
        color: var(--primary-color);
    }
    
    .lightbox-close {
        top: 20px;
        right: 20px;
    }
    
    .lightbox-prev {
        top: 50%;
        left: 20px;
        transform: translateY(-50%);
    }
    
    .lightbox-next {
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
    }
    
    /* Responsive styles */
    @media (max-width: 1200px) {
        .gallery-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    @media (max-width: 992px) {
        .gallery-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 576px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .filter-categories {
            justify-content: flex-start;
            overflow-x: auto;
            padding-bottom: var(--spacing-sm);
            display: flex;
            flex-wrap: nowrap;
        }
        
        .filter-category {
            flex: 0 0 auto;
        }
    }
</style>

<!-- Custom JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize gallery lightbox
        const galleryLinks = document.querySelectorAll('.gallery-link');
        
        if (galleryLinks.length > 0) {
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
                
                // Close lightbox
                const closeBtn = lightbox.querySelector('.lightbox-close');
                closeBtn.addEventListener('click', function() {
                    lightbox.classList.remove('active');
                });
                
                // Close on outside click
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
            
            // Gallery link click event
            galleryLinks.forEach(function(link, index) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const imageUrl = this.getAttribute('href');
                    const imageTitle = this.getAttribute('title') || '';
                    
                    const lightboxImage = lightbox.querySelector('.lightbox-image');
                    const lightboxCaption = lightbox.querySelector('.lightbox-caption');
                    
                    lightboxImage.src = imageUrl;
                    lightboxCaption.textContent = imageTitle;
                    
                    // Show lightbox
                    lightbox.classList.add('active');
                    
                    // Update prev/next buttons
                    const prevBtn = lightbox.querySelector('.lightbox-prev');
                    const nextBtn = lightbox.querySelector('.lightbox-next');
                    
                    prevBtn.onclick = function() {
                        const prevIndex = (index - 1 + galleryLinks.length) % galleryLinks.length;
                        const prevLink = galleryLinks[prevIndex];
                        
                        lightboxImage.src = prevLink.getAttribute('href');
                        lightboxCaption.textContent = prevLink.getAttribute('title') || '';
                        
                        index = prevIndex;
                    };
                    
                    nextBtn.onclick = function() {
                        const nextIndex = (index + 1) % galleryLinks.length;
                        const nextLink = galleryLinks[nextIndex];
                        
                        lightboxImage.src = nextLink.getAttribute('href');
                        lightboxCaption.textContent = nextLink.getAttribute('title') || '';
                        
                        index = nextIndex;
                    };
                });
            });
        }
    });
</script>