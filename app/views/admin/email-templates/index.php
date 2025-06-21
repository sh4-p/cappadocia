<!-- Email Templates Index View - app/views/admin/email-templates/index.php -->

<div class="admin-content-header">
    <div class="content-header-left">
        <h1 class="content-header-title">
            <i class="material-icons">email</i>
            <?php _e('email_templates'); ?>
        </h1>
        <p class="content-header-subtitle"><?php _e('manage_email_templates'); ?></p>
    </div>
    <div class="content-header-right">
        <a href="<?php echo $adminUrl; ?>/email-templates/create" class="btn btn-primary">
            <i class="material-icons">add</i>
            <?php _e('add_email_template'); ?>
        </a>
    </div>
</div>

<div class="admin-content-body">
    <div class="card">
        <div class="card-body">
            <?php if (empty($templates)): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="material-icons">email</i>
                    </div>
                    <h3><?php _e('no_email_templates'); ?></h3>
                    <p><?php _e('no_email_templates_desc'); ?></p>
                    <a href="<?php echo $adminUrl; ?>/email-templates/create" class="btn btn-primary">
                        <i class="material-icons">add</i>
                        <?php _e('add_first_template'); ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th><?php _e('template_key'); ?></th>
                                <th><?php _e('name'); ?></th>
                                <th><?php _e('subject'); ?></th>
                                <th><?php _e('variables'); ?></th>
                                <th><?php _e('status'); ?></th>
                                <th><?php _e('updated_at'); ?></th>
                                <th><?php _e('actions'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($templates as $template): ?>
                                <tr>
                                    <td>
                                        <code class="template-key"><?php echo htmlspecialchars($template['template_key']); ?></code>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($template['name']); ?></strong>
                                        <?php if (!empty($template['description'])): ?>
                                            <br><small class="text-muted"><?php echo htmlspecialchars($template['description']); ?></small>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="email-subject"><?php echo htmlspecialchars(substr($template['subject'], 0, 50)); ?><?php echo strlen($template['subject']) > 50 ? '...' : ''; ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                        $variables = json_decode($template['variables'], true) ?: [];
                                        if (!empty($variables)): 
                                        ?>
                                            <div class="variables-list">
                                                <?php foreach (array_slice($variables, 0, 3) as $variable): ?>
                                                    <span class="variable-tag">{{<?php echo htmlspecialchars($variable); ?>}}</span>
                                                <?php endforeach; ?>
                                                <?php if (count($variables) > 3): ?>
                                                    <span class="variable-count">+<?php echo count($variables) - 3; ?> <?php _e('more'); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted"><?php _e('no_variables'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge <?php echo $template['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                            <?php echo $template['is_active'] ? __('active') : __('inactive'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span class="date-time"><?php echo date('M j, Y', strtotime($template['updated_at'])); ?></span>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($template['updated_at'])); ?></small>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo $adminUrl; ?>/email-templates/view/<?php echo $template['id']; ?>" 
                                               class="btn btn-sm btn-info" title="<?php _e('view'); ?>">
                                                <i class="material-icons">visibility</i>
                                            </a>
                                            <a href="<?php echo $adminUrl; ?>/email-templates/preview/<?php echo $template['id']; ?>" 
                                               class="btn btn-sm btn-secondary" title="<?php _e('preview'); ?>">
                                                <i class="material-icons">preview</i>
                                            </a>
                                            <a href="<?php echo $adminUrl; ?>/email-templates/edit/<?php echo $template['id']; ?>" 
                                               class="btn btn-sm btn-warning" title="<?php _e('edit'); ?>">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="<?php echo $adminUrl; ?>/email-templates/toggle-status/<?php echo $template['id']; ?>" 
                                               class="btn btn-sm <?php echo $template['is_active'] ? 'btn-danger' : 'btn-success'; ?>" 
                                               title="<?php echo $template['is_active'] ? __('deactivate') : __('activate'); ?>">
                                                <i class="material-icons"><?php echo $template['is_active'] ? 'visibility_off' : 'visibility'; ?></i>
                                            </a>
                                            <a href="<?php echo $adminUrl; ?>/email-templates/delete/<?php echo $template['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               title="<?php _e('delete'); ?>"
                                               onclick="return confirm('<?php _e('confirm_delete'); ?>')">
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

    <!-- Available Templates Info -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title">
                <i class="material-icons">info</i>
                <?php _e('available_template_keys'); ?>
            </h5>
        </div>
        <div class="card-body">
            <div class="template-keys-grid">
                <?php foreach ($availableKeys as $key => $info): ?>
                    <div class="template-key-card">
                        <h6><code><?php echo htmlspecialchars($key); ?></code></h6>
                        <p class="text-muted"><?php echo htmlspecialchars($info['description']); ?></p>
                        <div class="variables-info">
                            <strong><?php _e('available_variables'); ?>:</strong>
                            <div class="mt-1">
                                <?php foreach ($info['variables'] as $variable): ?>
                                    <span class="variable-tag small">{{<?php echo htmlspecialchars($variable); ?>}}</span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<style>
.template-key {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
}

.email-subject {
    font-size: 13px;
    color: #666;
}

.variables-list {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
}

.variable-tag {
    background: #e3f2fd;
    color: #1976d2;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 11px;
    font-family: 'Courier New', monospace;
}

.variable-count {
    background: #f5f5f5;
    color: #666;
    padding: 2px 6px;
    border-radius: 12px;
    font-size: 11px;
}

.template-keys-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.template-key-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 15px;
    background: #fafafa;
}

.template-key-card h6 {
    margin: 0 0 8px 0;
    color: #333;
}

.template-key-card p {
    margin: 0 0 12px 0;
    font-size: 13px;
}

.variables-info strong {
    font-size: 12px;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-active {
    background: #e8f5e8;
    color: #2e7d32;
}

.status-inactive {
    background: #ffeaa7;
    color: #d68910;
}

.action-buttons {
    display: flex;
    gap: 4px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-icon {
    font-size: 64px;
    color: #ccc;
    margin-bottom: 20px;
}

.empty-icon i {
    font-size: inherit;
}

.date-time {
    white-space: nowrap;
}
</style>