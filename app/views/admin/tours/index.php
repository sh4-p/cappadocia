<?php
/**
 * Admin Tours List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('tours'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/tours/create" class="btn btn-primary">
            <i class="material-icons">add</i>
            <span><?php _e('add_tour'); ?></span>
        </a>
        <a href="<?php echo $adminUrl; ?>/categories" class="btn btn-light">
            <i class="material-icons">category</i>
            <span><?php _e('manage_categories'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($tours)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">explore_off</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_tours_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_tours_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/tours/create" class="btn btn-primary">
                    <i class="material-icons">add</i>
                    <?php _e('add_first_tour'); ?>
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
                            <th><?php _e('category'); ?></th>
                            <th><?php _e('price'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('featured'); ?></th>
                            <th><?php _e('created_at'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($tours as $tour): ?>
                            <tr>
                                <td><?php echo $tour['id']; ?></td>
                                <td>
                                    <div class="table-image">
                                        <img src="<?php echo $uploadsUrl . '/tours/' . $tour['featured_image']; ?>" alt="<?php echo $tour['name']; ?>">
                                    </div>
                                </td>
                                <td>
                                    <a href="<?php echo $adminUrl; ?>/tours/edit/<?php echo $tour['id']; ?>">
                                        <?php echo $tour['name']; ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($tour['category_name']): ?>
                                        <a href="<?php echo $adminUrl; ?>/categories/edit/<?php echo $tour['category_id']; ?>">
                                            <?php echo $tour['category_name']; ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted"><?php _e('no_category'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($tour['discount_price'] > 0): ?>
                                        <span class="price-discount">
                                            <del><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></del>
                                            <span><?php echo $settings['currency_symbol'] . number_format($tour['discount_price'], 2); ?></span>
                                        </span>
                                    <?php else: ?>
                                        <span><?php echo $settings['currency_symbol'] . number_format($tour['price'], 2); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $tour['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $tour['is_active'] ? __('active') : __('inactive'); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $tour['is_featured'] ? 'featured' : 'normal'; ?>">
                                        <?php echo $tour['is_featured'] ? __('yes') : __('no'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y', strtotime($tour['created_at'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/tours/<?php echo $tour['slug']; ?>" class="action-btn" title="<?php _e('view'); ?>" target="_blank">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/tours/edit/<?php echo $tour['id']; ?>" class="action-btn" title="<?php _e('edit'); ?>">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/tours/delete/<?php echo $tour['id']; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_tour_confirm'); ?>">
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
.price-discount del {
    color: var(--gray-500);
    font-size: 0.85em;
    display: block;
}

.table-image {
    width: 60px;
    height: 40px;
    border-radius: var(--border-radius-sm);
    overflow: hidden;
}

.table-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
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

.status-featured {
    background-color: rgba(255, 193, 7, 0.1);
    color: #ffc107;
}

.status-normal {
    background-color: rgba(108, 117, 125, 0.1);
    color: var(--gray-600);
}
</style>