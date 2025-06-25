<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">edit</i> <?php _e('edit_subscriber'); ?></h1>
        <p><?php _e('edit_newsletter_subscriber'); ?> - <?php echo htmlspecialchars($subscriber['email']); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-outline-secondary">
            <i class="material-icons">arrow_back</i>
            <?php _e('back_to_subscribers'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?php _e('subscriber_information'); ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $adminUrl; ?>/newsletter/edit-subscriber/<?php echo $subscriber['id']; ?>">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label"><?php _e('email_address'); ?> <span class="required">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($subscriber['email']); ?>" required>
                                <small class="form-text text-muted"><?php _e('email_address_help'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label"><?php _e('name'); ?></label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="<?php echo htmlspecialchars($subscriber['name'] ?? ''); ?>">
                                <small class="form-text text-muted"><?php _e('subscriber_name_help'); ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label"><?php _e('status'); ?></label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" <?php echo $subscriber['status'] === 'active' ? 'selected' : ''; ?>>
                                <?php _e('active'); ?>
                            </option>
                            <option value="pending" <?php echo $subscriber['status'] === 'pending' ? 'selected' : ''; ?>>
                                <?php _e('pending'); ?>
                            </option>
                            <option value="inactive" <?php echo $subscriber['status'] === 'inactive' ? 'selected' : ''; ?>>
                                <?php _e('inactive'); ?>
                            </option>
                            <option value="unsubscribed" <?php echo $subscriber['status'] === 'unsubscribed' ? 'selected' : ''; ?>>
                                <?php _e('unsubscribed'); ?>
                            </option>
                        </select>
                        <small class="form-text text-muted"><?php _e('subscriber_status_help'); ?></small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons">save</i>
                            <?php _e('update_subscriber'); ?>
                        </button>
                        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-secondary">
                            <?php _e('cancel'); ?>
                        </a>
                        <a href="<?php echo $adminUrl; ?>/newsletter/delete-subscriber/<?php echo $subscriber['id']; ?>" 
                           class="btn btn-danger float-end"
                           onclick="return confirm('<?php _e('confirm_delete_subscriber'); ?>')">
                            <i class="material-icons">delete</i>
                            <?php _e('delete_subscriber'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Subscriber Info -->
        <div class="card">
            <div class="card-header">
                <h5><i class="material-icons">info</i> <?php _e('subscriber_details'); ?></h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong><?php _e('current_status'); ?>:</strong></td>
                        <td>
                            <span class="badge badge-<?php 
                                echo $subscriber['status'] === 'active' ? 'success' : 
                                    ($subscriber['status'] === 'pending' ? 'warning' : 
                                    ($subscriber['status'] === 'unsubscribed' ? 'danger' : 'secondary')); 
                            ?>">
                                <?php _e('status_' . $subscriber['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('token'); ?>:</strong></td>
                        <td>
                            <code class="small"><?php echo substr($subscriber['token'], 0, 16); ?>...</code>
                        </td>
                    </tr>
                    <tr>
                        <td><strong><?php _e('created_at'); ?>:</strong></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($subscriber['created_at'])); ?></td>
                    </tr>
                    <?php if ($subscriber['subscribed_at']): ?>
                    <tr>
                        <td><strong><?php _e('subscribed_at'); ?>:</strong></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($subscriber['subscribed_at'])); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if ($subscriber['unsubscribed_at']): ?>
                    <tr>
                        <td><strong><?php _e('unsubscribed_at'); ?>:</strong></td>
                        <td><?php echo date('d/m/Y H:i', strtotime($subscriber['unsubscribed_at'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        <!-- Help Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="material-icons">help</i> <?php _e('help'); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php _e('subscriber_statuses'); ?></h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <span class="badge badge-success"><?php _e('active'); ?></span><br>
                        <small class="text-muted"><?php _e('active_status_description'); ?></small>
                    </li>
                    <li class="mb-2">
                        <span class="badge badge-warning"><?php _e('pending'); ?></span><br>
                        <small class="text-muted"><?php _e('pending_status_description'); ?></small>
                    </li>
                    <li class="mb-2">
                        <span class="badge badge-secondary"><?php _e('inactive'); ?></span><br>
                        <small class="text-muted"><?php _e('inactive_status_description'); ?></small>
                    </li>
                    <li class="mb-2">
                        <span class="badge badge-danger"><?php _e('unsubscribed'); ?></span><br>
                        <small class="text-muted"><?php _e('unsubscribed_status_description'); ?></small>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Action Links -->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="material-icons">link</i> <?php _e('action_links'); ?></h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label"><?php _e('confirmation_link'); ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" 
                               value="<?php echo $appUrl . '/' . $currentLang . '/newsletter/confirm/' . $subscriber['token']; ?>" 
                               readonly id="confirm-link">
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('confirm-link')">
                            <i class="material-icons">copy</i>
                        </button>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label"><?php _e('unsubscribe_link'); ?></label>
                    <div class="input-group">
                        <input type="text" class="form-control form-control-sm" 
                               value="<?php echo $appUrl . '/' . $currentLang . '/newsletter/unsubscribe/' . $subscriber['token']; ?>" 
                               readonly id="unsubscribe-link">
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="copyToClipboard('unsubscribe-link')">
                            <i class="material-icons">copy</i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.required {
    color: #dc3545;
}

.form-actions {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
    margin-top: 20px;
}

.card .badge {
    font-size: 0.75em;
}

code {
    font-size: 0.8em;
}
</style>

<script>
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    element.setSelectionRange(0, 99999);
    document.execCommand('copy');
    
    // Show feedback
    const button = element.nextElementSibling;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="material-icons">check</i>';
    button.classList.add('btn-success');
    button.classList.remove('btn-outline-secondary');
    
    setTimeout(() => {
        button.innerHTML = originalText;
        button.classList.remove('btn-success');
        button.classList.add('btn-outline-secondary');
    }, 2000);
}
</script>