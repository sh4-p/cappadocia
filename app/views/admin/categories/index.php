<?php
/**
 * Admin Categories List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('categories'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/categories/create" class="btn btn-primary">
            <i class="material-icons">add</i>
            <span><?php _e('add_category'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">category</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_categories_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_categories_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/categories/create" class="btn btn-primary">
                    <i class="material-icons">add</i>
                    <?php _e('add_first_category'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th><?php _e('id'); ?></th>
                            <th><?php _e('image'); ?></th>
                            <th><?php _e('name'); ?></th>
                            <th><?php _e('tours'); ?></th>
                            <th><?php _e('order'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?php echo $category['id']; ?></td>
                                <td>
                                    <div class="table-image">
                                        <?php if ($category['image']): ?>
                                            <img src="<?php echo $uploadsUrl . '/categories/' . $category['image']; ?>" alt="<?php echo $category['name']; ?>">
                                        <?php else: ?>
                                            <div class="no-image"><i class="material-icons">image</i></div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo $adminUrl; ?>/categories/edit/<?php echo $category['id']; ?>">
                                        <?php echo $category['name']; ?>
                                    </a>
                                </td>
                                <td>
                                    <a href="<?php echo $adminUrl; ?>/tours?category=<?php echo $category['slug']; ?>">
                                        <?php echo $category['tour_count']; ?> <?php _e('tours'); ?>
                                    </a>
                                </td>
                                <td><?php echo $category['order_number']; ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $category['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $category['is_active'] ? __('active') : __('inactive'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours?category=<?php echo $category['slug']; ?>" class="action-btn" title="<?php _e('view'); ?>" target="_blank">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/categories/edit/<?php echo $category['id']; ?>" class="action-btn" title="<?php _e('edit'); ?>">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/categories/delete/<?php echo $category['id']; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_category_confirm'); ?>">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.table-image {
    width: 60px;
    height: 40px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    background-color: var(--gray-100);
}

.table-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-image {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-400);
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
}

.status-active {
    background-color: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
}

.status-inactive {
    background-color: rgba(108, 117, 125, 0.1);
    color: var(--gray-600);
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