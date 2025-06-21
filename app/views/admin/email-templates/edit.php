<!-- Email Templates Edit View - app/views/admin/email-templates/edit.php -->

<div class="admin-content-header">
    <div class="content-header-left">
        <h1 class="content-header-title">
            <i class="material-icons">edit</i>
            <?php _e('edit_email_template'); ?>
        </h1>
        <p class="content-header-subtitle"><?php echo htmlspecialchars($template['name']); ?></p>
    </div>
    <div class="content-header-right">
        <a href="<?php echo $adminUrl; ?>/email-templates/view/<?php echo $template['id']; ?>" class="btn btn-info">
            <i class="material-icons">visibility</i>
            <?php _e('view_template'); ?>
        </a>
        <a href="<?php echo $adminUrl; ?>/email-templates" class="btn btn-secondary">
            <i class="material-icons">arrow_back</i>
            <?php _e('back_to_templates'); ?>
        </a>
    </div>
</div>

<div class="admin-content-body">
    <form method="POST" class="email-template-form">
        <div class="row">
            <!-- Template Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">settings</i>
                            <?php _e('template_details'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="template_key" class="form-label required">
                                <?php _e('template_key'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="template_key" id="template_key" class="form-control" 
                                   value="<?php echo htmlspecialchars($template['template_key']); ?>" 
                                   placeholder="<?php _e('enter_template_key'); ?>" required>
                            <small class="form-help"><?php _e('template_key_edit_help'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-label required">
                                <?php _e('template_name'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($template['name']); ?>" 
                                   placeholder="<?php _e('enter_template_name'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">
                                <?php _e('description'); ?>
                            </label>
                            <textarea name="description" id="description" class="form-control" rows="2" 
                                      placeholder="<?php _e('enter_description'); ?>"><?php echo htmlspecialchars($template['description'] ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="form-label required">
                                <?php _e('email_subject'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="subject" id="subject" class="form-control" 
                                   value="<?php echo htmlspecialchars($template['subject']); ?>" 
                                   placeholder="<?php _e('enter_email_subject'); ?>" required>
                            <small class="form-help"><?php _e('subject_help'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="body" class="form-label required">
                                <?php _e('email_body'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="body" id="body" class="form-control code-editor" rows="15" 
                                      placeholder="<?php _e('enter_email_body'); ?>" required><?php echo htmlspecialchars($template['body']); ?></textarea>
                            <small class="form-help"><?php _e('body_help'); ?></small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                       <?php echo $template['is_active'] ? 'checked' : ''; ?>>
                                <label for="is_active" class="form-check-label">
                                    <?php _e('template_active'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Info Sidebar -->
            <div class="col-lg-4">
                <!-- Current Variables -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">code</i>
                            <?php _e('template_variables'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="current-variables">
                            <?php if (!empty($variables)): ?>
                                <div class="variables-list">
                                    <?php foreach ($variables as $variable): ?>
                                        <div class="variable-item">
                                            <code class="variable-code">{{<?php echo htmlspecialchars($variable); ?>}}</code>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php else: ?>
                                <p class="text-muted"><?php _e('no_variables_found'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Template Actions -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">play_arrow</i>
                            <?php _e('template_actions'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="template-actions">
                            <button type="button" class="btn btn-info btn-block" onclick="previewTemplate()">
                                <i class="material-icons">preview</i>
                                <?php _e('preview_template'); ?>
                            </button>
                            <button type="button" class="btn btn-secondary btn-block" onclick="showTestModal()">
                                <i class="material-icons">send</i>
                                <?php _e('send_test_email'); ?>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Template Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">info</i>
                            <?php _e('template_info'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="template-meta">
                            <div class="meta-item">
                                <strong><?php _e('created_at'); ?>:</strong>
                                <span><?php echo date('M j, Y H:i', strtotime($template['created_at'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <strong><?php _e('updated_at'); ?>:</strong>
                                <span><?php echo date('M j, Y H:i', strtotime($template['updated_at'])); ?></span>
                            </div>
                            <div class="meta-item">
                                <strong><?php _e('status'); ?>:</strong>
                                <span class="status-badge <?php echo $template['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $template['is_active'] ? __('active') : __('inactive'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="material-icons">save</i>
                                <?php _e('update_template'); ?>
                            </button>
                            <a href="<?php echo $adminUrl; ?>/email-templates" class="btn btn-secondary btn-block">
                                <i class="material-icons">cancel</i>
                                <?php _e('cancel'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="material-icons">preview</i>
                    <?php _e('template_preview'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="preview-container">
                    <div class="preview-subject">
                        <strong><?php _e('subject'); ?>:</strong>
                        <div id="preview-subject-content"></div>
                    </div>
                    <div class="preview-body">
                        <strong><?php _e('body'); ?>:</strong>
                        <div id="preview-body-content"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Test Email Modal -->
<div class="modal fade" id="testModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="material-icons">send</i>
                    <?php _e('send_test_email'); ?>
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?php echo $adminUrl; ?>/email-templates/test-send/<?php echo $template['id']; ?>" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="test_email" class="form-label required">
                            <?php _e('test_email_address'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="test_email" id="test_email" class="form-control" 
                               placeholder="<?php _e('enter_test_email'); ?>" required>
                        <small class="form-help"><?php _e('test_email_help'); ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php _e('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons">send</i>
                        <?php _e('send_test'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subjectInput = document.getElementById('subject');
    const bodyTextarea = document.getElementById('body');
    
    // Auto-update variables when content changes
    function updateVariables() {
        const subject = subjectInput.value;
        const body = bodyTextarea.value;
        const content = subject + ' ' + body;
        
        // Extract variables using regex
        const variableRegex = /\{\{([^}]+)\}\}/g;
        const variables = new Set();
        let match;
        
        while ((match = variableRegex.exec(content)) !== null) {
            // Remove conditional syntax markers
            const variable = match[1].replace(/^#/, '').replace(/^\/$/, '');
            variables.add(variable);
        }
        
        updateVariablesDisplay(Array.from(variables).sort());
    }
    
    function updateVariablesDisplay(variables) {
        const container = document.getElementById('current-variables');
        
        if (variables.length === 0) {
            container.innerHTML = '<p class="text-muted"><?php _e('no_variables_found'); ?></p>';
            return;
        }
        
        let html = '<div class="variables-list">';
        variables.forEach(function(variable) {
            html += '<div class="variable-item">';
            html += '<code class="variable-code">{{' + variable + '}}</code>';
            html += '</div>';
        });
        html += '</div>';
        
        container.innerHTML = html;
    }
    
    // Listen for content changes
    subjectInput.addEventListener('input', updateVariables);
    bodyTextarea.addEventListener('input', updateVariables);
    
    // Initialize variables display
    updateVariables();
});

function previewTemplate() {
    const templateId = <?php echo $template['id']; ?>;
    
    fetch('<?php echo $adminUrl; ?>/email-templates/preview/' + templateId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('preview-subject-content').innerHTML = data.subject;
            document.getElementById('preview-body-content').innerHTML = data.body;
            $('#previewModal').modal('show');
        } else {
            alert('<?php _e('preview_failed'); ?>');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('<?php _e('preview_failed'); ?>');
    });
}

function showTestModal() {
    $('#testModal').modal('show');
}
</script>

<style>
.email-template-form .form-group {
    margin-bottom: 20px;
}

.code-editor {
    font-family: 'Courier New', monospace;
    font-size: 13px;
}

.variables-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.variable-item {
    display: flex;
    align-items: center;
}

.variable-code {
    background: #e3f2fd;
    color: #1976d2;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 12px;
    cursor: pointer;
    transition: background-color 0.2s;
}

.variable-code:hover {
    background: #bbdefb;
}

.template-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.template-meta {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.meta-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
}

.meta-item strong {
    color: #666;
    font-size: 12px;
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

.form-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-help {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #666;
}

.preview-container {
    padding: 20px 0;
}

.preview-subject,
.preview-body {
    margin-bottom: 20px;
}

.preview-subject strong,
.preview-body strong {
    display: block;
    margin-bottom: 10px;
    color: #333;
}

#preview-subject-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

#preview-body-content {
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 20px;
    background: white;
    min-height: 200px;
}
</style>