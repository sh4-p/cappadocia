<?php
/**
 * Admin Testimonials List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('testimonials'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/testimonials/create" class="btn btn-primary">
            <i class="material-icons">add</i>
            <span><?php _e('add_testimonial'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($testimonials)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">format_quote</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_testimonials_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_testimonials_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/testimonials/create" class="btn btn-primary">
                    <i class="material-icons">add</i>
                    <?php _e('add_first_testimonial'); ?>
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
                            <th><?php _e('position'); ?></th>
                            <th><?php _e('rating'); ?></th>
                            <th><?php _e('content'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($testimonials as $testimonial): ?>
                            <tr>
                                <td><?php echo $testimonial['id']; ?></td>
                                <td>
                                    <?php if ($testimonial['image']): ?>
                                        <img src="<?php echo $uploadsUrl; ?>/testimonials/<?php echo $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>" class="testimonial-thumbnail">
                                    <?php else: ?>
                                        <div class="testimonial-thumbnail-placeholder">
                                            <i class="material-icons">person</i>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $testimonial['name']; ?></td>
                                <td><?php echo $testimonial['position']; ?></td>
                                <td>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="material-icons <?php echo $i <= $testimonial['rating'] ? 'active' : ''; ?>">star</i>
                                        <?php endfor; ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="content-preview">
                                        <?php echo mb_substr(strip_tags($testimonial['content']), 0, 50) . (mb_strlen(strip_tags($testimonial['content'])) > 50 ? '...' : ''); ?>
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo $adminUrl; ?>/testimonials/toggle-status/<?php echo $testimonial['id']; ?>" class="status-badge status-<?php echo $testimonial['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $testimonial['is_active'] ? __('active') : __('inactive'); ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $adminUrl; ?>/testimonials/edit/<?php echo $testimonial['id']; ?>" class="action-btn" title="<?php _e('edit'); ?>">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/testimonials/delete/<?php echo $testimonial['id']; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_testimonial_confirm'); ?>">
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
.testimonial-thumbnail {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius-circle);
    object-fit: cover;
}

.testimonial-thumbnail-placeholder {
    width: 50px;
    height: 50px;
    border-radius: var(--border-radius-circle);
    background-color: var(--gray-300);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
}

.rating {
    display: flex;
    align-items: center;
}

.rating .material-icons {
    font-size: 16px;
    color: var(--gray-400);
}

.rating .material-icons.active {
    color: var(--warning-color);
}

.content-preview {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.status-active {
    background-color: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
}

.status-active:hover {
    background-color: rgba(40, 167, 69, 0.2);
}

.status-inactive {
    background-color: rgba(108, 117, 125, 0.1);
    color: var(--gray-600);
}

.status-inactive:hover {
    background-color: rgba(108, 117, 125, 0.2);
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