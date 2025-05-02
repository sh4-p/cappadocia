<?php
/**
 * Admin Languages List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('languages'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/languages/create" class="btn btn-primary">
            <i class="material-icons">add</i>
            <span><?php _e('add_language'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($languages)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">language</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_languages_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_languages_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/languages/create" class="btn btn-primary">
                    <i class="material-icons">add</i>
                    <?php _e('add_first_language'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th><?php _e('id'); ?></th>
                            <th><?php _e('flag'); ?></th>
                            <th><?php _e('name'); ?></th>
                            <th><?php _e('code'); ?></th>
                            <th><?php _e('default'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($languages as $language): ?>
                            <tr>
                                <td><?php echo $language['id']; ?></td>
                                <td>
                                    <div class="language-flag">
                                        <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $language['flag']; ?>" alt="<?php echo $language['name']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo $adminUrl; ?>/languages/edit/<?php echo $language['id']; ?>">
                                        <?php echo $language['name']; ?>
                                    </a>
                                </td>
                                <td><?php echo $language['code']; ?></td>
                                <td>
                                    <?php if ($language['is_default']): ?>
                                        <span class="badge badge-primary"><?php _e('yes'); ?></span>
                                    <?php else: ?>
                                        <a href="<?php echo $adminUrl; ?>/languages/set-default/<?php echo $language['id']; ?>" class="badge badge-light" title="<?php _e('set_as_default'); ?>">
                                            <?php _e('no'); ?>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $language['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $language['is_active'] ? __('active') : __('inactive'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $adminUrl; ?>/languages/edit/<?php echo $language['id']; ?>" class="action-btn" title="<?php _e('edit'); ?>">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/languages/toggle-status/<?php echo $language['id']; ?>" class="action-btn" title="<?php echo $language['is_active'] ? __('deactivate') : __('activate'); ?>">
                                            <i class="material-icons"><?php echo $language['is_active'] ? 'toggle_on' : 'toggle_off'; ?></i>
                                        </a>
                                        <?php if (!$language['is_default']): ?>
                                            <a href="<?php echo $adminUrl; ?>/languages/delete/<?php echo $language['id']; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_language_confirm'); ?>">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        <?php endif; ?>
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
.language-flag {
    width: 40px;
    height: 30px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
}

.language-flag img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.badge {
    display: inline-block;
    padding: 0.25em 0.75em;
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    border-radius: var(--border-radius-sm);
    text-transform: uppercase;
}

.badge-primary {
    background-color: var(--primary-color);
    color: var(--white-color);
}

.badge-light {
    background-color: var(--gray-200);
    color: var(--gray-700);
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