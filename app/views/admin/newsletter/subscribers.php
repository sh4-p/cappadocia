<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">people</i> <?php _e('newsletter_subscribers'); ?></h1>
        <p><?php _e('manage_newsletter_subscribers'); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/newsletter/export-subscribers<?php echo $status ? '?status=' . $status : ''; ?>" class="btn btn-outline-secondary">
            <i class="material-icons">file_download</i>
            <?php _e('export'); ?>
        </a>
        <a href="<?php echo $adminUrl; ?>/newsletter/import-subscribers" class="btn btn-outline-primary">
            <i class="material-icons">file_upload</i>
            <?php _e('import'); ?>
        </a>
        <a href="<?php echo $adminUrl; ?>/newsletter/add-subscriber" class="btn btn-primary">
            <i class="material-icons">person_add</i>
            <?php _e('add_subscriber'); ?>
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row align-items-end">
            <div class="col-md-3">
                <label for="status" class="form-label"><?php _e('status'); ?></label>
                <select name="status" id="status" class="form-control">
                    <option value=""><?php _e('all_statuses'); ?></option>
                    <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>><?php _e('active'); ?> (<?php echo $stats['active']; ?>)</option>
                    <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>><?php _e('pending'); ?> (<?php echo $stats['pending']; ?>)</option>
                    <option value="unsubscribed" <?php echo $status === 'unsubscribed' ? 'selected' : ''; ?>><?php _e('unsubscribed'); ?> (<?php echo $stats['unsubscribed']; ?>)</option>
                    <option value="inactive" <?php echo $status === 'inactive' ? 'selected' : ''; ?>><?php _e('inactive'); ?> (<?php echo $stats['inactive']; ?>)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label"><?php _e('search'); ?></label>
                <input type="text" name="search" id="search" class="form-control" 
                       placeholder="<?php _e('search_email_or_name'); ?>" 
                       value="<?php echo htmlspecialchars($search ?? ''); ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">
                    <i class="material-icons">search</i>
                    <?php _e('search'); ?>
                </button>
            </div>
            <div class="col-md-3 text-end">
                <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-outline-secondary">
                    <?php _e('clear_filters'); ?>
                </a>
            </div>
        </form>
    </div>
</div>

<?php if (!empty($subscribers)): ?>
<!-- Bulk Actions -->
<form id="bulk-form" method="POST" action="<?php echo $adminUrl; ?>/newsletter/bulk-action">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4><?php _e('subscribers'); ?> (<?php echo number_format($totalSubscribers); ?>)</h4>
                </div>
                <div class="col-md-6 text-end">
                    <div class="bulk-actions" style="display: none;">
                        <select name="bulk_action" class="form-control d-inline-block" style="width: auto;">
                            <option value=""><?php _e('bulk_actions'); ?></option>
                            <option value="activate"><?php _e('activate'); ?></option>
                            <option value="deactivate"><?php _e('deactivate'); ?></option>
                            <option value="unsubscribe"><?php _e('unsubscribe'); ?></option>
                            <option value="delete"><?php _e('delete'); ?></option>
                        </select>
                        <button type="submit" class="btn btn-primary" onclick="return confirm('<?php _e('confirm_bulk_action'); ?>')">
                            <?php _e('apply'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th width="50">
                                <input type="checkbox" id="select-all" class="form-check-input">
                            </th>
                            <th><?php _e('email'); ?></th>
                            <th><?php _e('name'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('subscribed_at'); ?></th>
                            <th><?php _e('created_at'); ?></th>
                            <th width="150"><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($subscribers as $subscriber): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_ids[]" 
                                       value="<?php echo $subscriber['id']; ?>" 
                                       class="form-check-input subscriber-checkbox">
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($subscriber['email'] ?? ''); ?></strong>
                            </td>
                            <td><?php echo htmlspecialchars($subscriber['name'] ?? '-'); ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $subscriber['status'] === 'active' ? 'success' : 
                                        ($subscriber['status'] === 'pending' ? 'warning' : 
                                        ($subscriber['status'] === 'unsubscribed' ? 'danger' : 'secondary')); 
                                ?>">
                                    <?php _e('status_' . $subscriber['status']); ?>
                                </span>
                            </td>
                            <td>
                                <?php if (!empty($subscriber['subscribed_at'])): ?>
                                    <?php echo date('d/m/Y H:i', strtotime($subscriber['subscribed_at'])); ?>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($subscriber['created_at'])); ?></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo $adminUrl; ?>/newsletter/edit-subscriber/<?php echo $subscriber['id']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="<?php _e('edit'); ?>">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <a href="<?php echo $adminUrl; ?>/newsletter/delete-subscriber/<?php echo $subscriber['id']; ?>" 
                                       class="btn btn-sm btn-outline-danger" 
                                       title="<?php _e('delete'); ?>"
                                       onclick="return confirm('<?php _e('confirm_delete_subscriber'); ?>')">
                                        <i class="material-icons">delete</i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="pagination-wrapper mt-4">
    <nav aria-label="<?php _e('pagination'); ?>">
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                        <?php _e('previous'); ?>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);
            ?>
            
            <?php for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?><?php echo $status ? '&status=' . urlencode($status) : ''; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>">
                        <?php _e('next'); ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>

<?php else: ?>
<!-- Empty State -->
<div class="card">
    <div class="card-body text-center py-5">
        <i class="material-icons" style="font-size: 64px; color: #ccc;">people_outline</i>
        <h4 class="mt-3"><?php _e('no_subscribers_found'); ?></h4>
        <p class="text-muted"><?php _e('no_subscribers_found_description'); ?></p>
        <?php if ($search || $status): ?>
            <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-outline-primary">
                <?php _e('clear_filters'); ?>
            </a>
        <?php else: ?>
            <a href="<?php echo $adminUrl; ?>/newsletter/add-subscriber" class="btn btn-primary">
                <?php _e('add_first_subscriber'); ?>
            </a>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.subscriber-checkbox');
    const bulkActions = document.querySelector('.bulk-actions');
    
    // Select all functionality
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkActions();
        });
    }
    
    // Individual checkbox changes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.subscriber-checkbox:checked').length;
            if (selectAll) {
                selectAll.checked = checkedCount === checkboxes.length;
                selectAll.indeterminate = checkedCount > 0 && checkedCount < checkboxes.length;
            }
            toggleBulkActions();
        });
    });
    
    function toggleBulkActions() {
        const checkedCount = document.querySelectorAll('.subscriber-checkbox:checked').length;
        if (bulkActions) {
            bulkActions.style.display = checkedCount > 0 ? 'block' : 'none';
        }
    }
});
</script>