<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">person_add</i> <?php _e('add_subscriber'); ?></h1>
        <p><?php _e('add_new_newsletter_subscriber'); ?></p>
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
                <form method="POST" action="<?php echo $adminUrl; ?>/newsletter/add-subscriber">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label"><?php _e('email_address'); ?> <span class="required">*</span></label>
                                <input type="email" name="email" id="email" class="form-control" 
                                       value="<?php echo htmlspecialchars($email ?? ''); ?>" required>
                                <small class="form-text text-muted"><?php _e('email_address_help'); ?></small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label"><?php _e('name'); ?></label>
                                <input type="text" name="name" id="name" class="form-control" 
                                       value="<?php echo htmlspecialchars($name ?? ''); ?>">
                                <small class="form-text text-muted"><?php _e('subscriber_name_help'); ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label"><?php _e('status'); ?></label>
                        <select name="status" id="status" class="form-control">
                            <option value="active" <?php echo ($status ?? 'active') === 'active' ? 'selected' : ''; ?>>
                                <?php _e('active'); ?>
                            </option>
                            <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>
                                <?php _e('pending'); ?>
                            </option>
                            <option value="inactive" <?php echo ($status ?? '') === 'inactive' ? 'selected' : ''; ?>>
                                <?php _e('inactive'); ?>
                            </option>
                        </select>
                        <small class="form-text text-muted"><?php _e('subscriber_status_help'); ?></small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="material-icons">save</i>
                            <?php _e('add_subscriber'); ?>
                        </button>
                        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-secondary">
                            <?php _e('cancel'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Help Card -->
        <div class="card">
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
                </ul>
                
                <hr>
                
                <h6><?php _e('tips'); ?></h6>
                <ul class="small text-muted">
                    <li><?php _e('tip_valid_email'); ?></li>
                    <li><?php _e('tip_confirmation_email'); ?></li>
                    <li><?php _e('tip_bulk_import'); ?></li>
                </ul>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="material-icons">flash_on</i> <?php _e('quick_actions'); ?></h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo $adminUrl; ?>/newsletter/import-subscribers" class="btn btn-outline-primary">
                        <i class="material-icons">file_upload</i>
                        <?php _e('import_from_csv'); ?>
                    </a>
                    <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-outline-secondary">
                        <i class="material-icons">people</i>
                        <?php _e('view_all_subscribers'); ?>
                    </a>
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
</style>