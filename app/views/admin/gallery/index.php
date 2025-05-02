<?php
/**
 * Admin Gallery List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('gallery'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/gallery/create" class="btn btn-primary">
            <i class="material-icons">add_photo_alternate</i>
            <span><?php _e('add_image'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($galleryItems)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">photo_library</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_gallery_items_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_gallery_items_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/gallery/create" class="btn btn-primary">
                    <i class="material-icons">add_photo_alternate</i>
                    <?php _e('add_first_image'); ?>
                </a>
            </div>
        <?php else: ?>
            <!-- Gallery Filters -->
            <div class="gallery-filters">
                <div class="row">
                    <div class="col-md-8">
                        <div class="filter-buttons">
                            <a href="<?php echo $adminUrl; ?>/gallery" class="filter-btn <?php echo !isset($_GET['tour_id']) && !isset($_GET['category']) ? 'active' : ''; ?>">
                                <?php _e('all'); ?>
                            </a>
                            <?php foreach ($categories as $category): ?>
                                <a href="<?php echo $adminUrl; ?>/gallery?category=<?php echo $category['id']; ?>" class="filter-btn <?php echo isset($_GET['category']) && $_GET['category'] == $category['id'] ? 'active' : ''; ?>">
                                    <?php echo $category['name']; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="filter-search">
                            <form action="<?php echo $adminUrl; ?>/gallery" method="get">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="<?php _e('search_gallery'); ?>" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="material-icons">search</i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gallery Grid -->
            <div class="gallery-admin-grid">
                <?php foreach ($galleryItems as $item): ?>
                    <div class="gallery-item">
                        <div class="gallery-image">
                            <img src="<?php echo $uploadsUrl . '/gallery/' . $item['image']; ?>" alt="<?php echo $item['title']; ?>">
                            <div class="gallery-actions">
                                <a href="<?php echo $adminUrl; ?>/gallery/edit/<?php echo $item['id']; ?>" class="gallery-action-btn" title="<?php _e('edit'); ?>">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a href="<?php echo $adminUrl; ?>/gallery/delete/<?php echo $item['id']; ?>" class="gallery-action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_image_confirm'); ?>">
                                    <i class="material-icons">delete</i>
                                </a>
                            </div>
                        </div>
                        <div class="gallery-caption">
                            <div class="gallery-title"><?php echo $item['title'] ?: __('no_title'); ?></div>
                            <?php if ($item['tour_name']): ?>
                                <div class="gallery-tour">
                                    <a href="<?php echo $adminUrl; ?>/tours/edit/<?php echo $item['tour_id']; ?>">
                                        <?php echo $item['tour_name']; ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?php echo $adminUrl; ?>/gallery?page=<?php echo $currentPage - 1; ?><?php echo isset($_GET['tour_id']) ? '&tour_id=' . $_GET['tour_id'] : ''; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="pagination-item">
                            <i class="material-icons">chevron_left</i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="<?php echo $adminUrl; ?>/gallery?page=<?php echo $i; ?><?php echo isset($_GET['tour_id']) ? '&tour_id=' . $_GET['tour_id'] : ''; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="pagination-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?php echo $adminUrl; ?>/gallery?page=<?php echo $currentPage + 1; ?><?php echo isset($_GET['tour_id']) ? '&tour_id=' . $_GET['tour_id'] : ''; ?><?php echo isset($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo isset($_GET['search']) ? '&search=' . urlencode($_GET['search']) : ''; ?>" class="pagination-item">
                            <i class="material-icons">chevron_right</i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .gallery-filters {
        margin-bottom: var(--spacing-lg);
    }

    .filter-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .filter-btn {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        background-color: var(--gray-200);
        color: var(--gray-700);
        transition: all var(--transition-fast);
    }

    .filter-btn.active,
    .filter-btn:hover {
        background-color: var(--primary-color);
        color: var(--white-color);
    }

    .filter-search {
        margin-bottom: var(--spacing-md);
    }

    .gallery-admin-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: var(--spacing-md);
        margin-top: var(--spacing-md);
    }

    .gallery-item {
        border-radius: var(--border-radius-md);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        background-color: var(--white-color);
        transition: transform var(--transition-medium), box-shadow var(--transition-medium);
    }

    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .gallery-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .gallery-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .gallery-actions {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        opacity: 0;
        transition: opacity var(--transition-fast);
    }

    .gallery-image:hover .gallery-actions {
        opacity: 1;
    }

    .gallery-action-btn {
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius-circle);
        background-color: var(--white-color);
        color: var(--dark-color);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background-color var(--transition-fast), color var(--transition-fast);
    }

    .gallery-action-btn:hover {
        background-color: var(--primary-color);
        color: var(--white-color);
    }

    .gallery-caption {
        padding: var(--spacing-md);
    }

    .gallery-title {
        font-weight: var(--font-weight-medium);
        margin-bottom: 0.25rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .gallery-tour {
        font-size: var(--font-size-sm);
        color: var(--gray-600);
    }

    @media (max-width: 1200px) {
        .gallery-admin-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 992px) {
        .gallery-admin-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .gallery-admin-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Delete confirmation
        const deleteBtns = document.querySelectorAll('.delete-btn');
        
        deleteBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                
                const confirmMessage = this.dataset.confirm || '<?php _e("delete_confirm"); ?>';
                
                if (confirm(confirmMessage)) {
                    window.location.href = this.getAttribute('href');
                }
            });
        });
    });
    </script>