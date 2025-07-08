<?php
/**
 * Admin Extras List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('tour_extras'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/extras/create" class="btn btn-primary">
            <i class="material-icons">add</i>
            <span><?php _e('add_extra'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($extras)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">add_circle</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_extras_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_extras_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/extras/create" class="btn btn-primary">
                    <i class="material-icons">add</i>
                    <?php _e('add_first_extra'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th><?php _e('id'); ?></th>
                            <th><?php _e('name'); ?></th>
                            <th><?php _e('base_price'); ?></th>
                            <th><?php _e('pricing_type'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($extras as $extra): ?>
                            <tr>
                                <td><?php echo $extra['id']; ?></td>
                                <td>
                                    <div class="extra-info">
                                        <div class="extra-name">
                                            <a href="<?php echo $adminUrl; ?>/extras/edit/<?php echo $extra['id']; ?>">
                                                <?php echo htmlspecialchars($extra['name']); ?>
                                            </a>
                                        </div>
                                        <?php if (!empty($extra['description'])): ?>
                                            <div class="extra-description">
                                                <?php echo htmlspecialchars(substr($extra['description'], 0, 80)) . (strlen($extra['description']) > 80 ? '...' : ''); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="price-display">
                                        <?php echo $settings['currency_symbol'] . number_format($extra['base_price'] ?? 0, 2); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="pricing-type-badge pricing-type-<?php echo $extra['pricing_type'] ?? 'fixed_group'; ?>">
                                        <?php 
                                        switch($extra['pricing_type'] ?? 'fixed_group') {
                                            case 'per_person':
                                                _e('per_person');
                                                break;
                                            case 'fixed_group':
                                                _e('fixed_group');
                                                break;
                                            case 'tiered':
                                                _e('tiered_pricing');
                                                break;
                                            default:
                                                _e('fixed_group');
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $extra['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $extra['is_active'] ? __('active') : __('inactive'); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $adminUrl; ?>/extras/edit/<?php echo $extra['id']; ?>" class="action-btn" title="<?php _e('edit'); ?>">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/extras/toggle-status/<?php echo $extra['id']; ?>" class="action-btn toggle-btn" title="<?php _e('toggle_status'); ?>">
                                            <i class="material-icons"><?php echo $extra['is_active'] ? 'visibility_off' : 'visibility'; ?></i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/extras/delete/<?php echo $extra['id']; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_extra_confirm'); ?>">
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
.extra-info .extra-name {
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 0.25rem;
}

.extra-info .extra-name a {
    color: inherit;
    text-decoration: none;
}

.extra-info .extra-name a:hover {
    color: var(--primary-color);
}

.extra-info .extra-description {
    font-size: var(--font-size-sm);
    color: var(--gray-600);
    line-height: 1.4;
}

.price-display {
    font-weight: 600;
    color: var(--primary-color);
}

.pricing-type-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-xs);
    font-weight: var(--font-weight-medium);
    text-transform: uppercase;
}

.pricing-type-per_person {
    background-color: rgba(42, 157, 143, 0.1);
    color: var(--secondary-color);
}

.pricing-type-fixed_group {
    background-color: rgba(244, 162, 97, 0.1);
    color: var(--accent-color);
}

.pricing-type-tiered {
    background-color: rgba(255, 107, 53, 0.1);
    color: var(--primary-color);
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

.actions {
    display: flex;
    gap: 0.5rem;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius-sm);
    color: var(--gray-600);
    text-decoration: none;
    transition: all 0.2s ease;
}

.action-btn:hover {
    background-color: var(--gray-100);
    color: var(--dark-color);
}

.action-btn.delete-btn:hover {
    background-color: var(--danger-color);
    border-color: var(--danger-color);
    color: white;
}

.toggle-btn:hover {
    background-color: var(--warning-color);
    border-color: var(--warning-color);
    color: white;
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
    
    // Toggle status confirmation
    const toggleBtns = document.querySelectorAll('.toggle-btn');
    
    toggleBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (confirm('<?php _e("toggle_status_confirm"); ?>')) {
                window.location.href = this.getAttribute('href');
            }
        });
    });
});
</script>