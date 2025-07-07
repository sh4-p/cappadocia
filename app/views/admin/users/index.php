<?php
/**
 * Admin Users List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('users'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/users/create" class="btn btn-primary">
            <i class="material-icons">person_add</i>
            <span><?php _e('add_user'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">people</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_users_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_users_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/users/create" class="btn btn-primary">
                    <i class="material-icons">person_add</i>
                    <?php _e('add_first_user'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th><?php _e('id'); ?></th>
                            <th><?php _e('name'); ?></th>
                            <th><?php _e('username'); ?></th>
                            <th><?php _e('email'); ?></th>
                            <th><?php _e('role'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('last_login'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td>
                                    <div class="user-info">
                                        <div class="user-name">
                                            <a href="<?php echo $adminUrl; ?>/users/edit/<?php echo $user['id']; ?>">
                                                <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo $user['username']; ?></td>
                                <td><?php echo $user['email']; ?></td>
                                <td><span class="badge badge-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span></td>
                                <td>
                                    <span class="status-badge status-<?php echo $user['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $user['is_active'] ? __('active') : __('inactive'); ?>
                                    </span>
                                </td>
                                <td><?php echo isset($user['last_login']) && $user['last_login'] ? date('d M Y, H:i', strtotime($user['last_login'])) : __('never'); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $adminUrl; ?>/users/edit/<?php echo $user['id']; ?>" class="action-btn" title="<?php _e('edit'); ?>">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <?php if ($user['id'] != $session->get('user_id')): ?>
                                            <a href="<?php echo $adminUrl; ?>/users/delete/<?php echo $user['id']; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_user_confirm'); ?>">
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
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: var(--border-radius-circle);
    overflow: hidden;
    background-color: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white-color);
    font-size: var(--font-size-sm);
    font-weight: var(--font-weight-bold);
}

.user-avatar img {
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

.badge-admin {
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
}

.badge-editor {
    background-color: rgba(40, 167, 69, 0.1);
    color: var(--success-color);
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