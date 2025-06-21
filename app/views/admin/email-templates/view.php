<!-- Email Templates View - app/views/admin/email-templates/view.php -->

<div class="admin-content-header">
    <div class="content-header-left">
        <h1 class="content-header-title">
            <i class="material-icons">visibility</i>
            <?php _e('view_email_template'); ?>
        </h1>
        <p class="content-header-subtitle"><?php echo htmlspecialchars($template['name']); ?></p>
    </div>
    <div class="content-header-right">
        <a href="<?php echo $adminUrl; ?>/email-templates/edit/<?php echo $template['id']; ?>" class="btn btn-warning">
            <i class="material-icons">edit</i>
            <?php _e('edit_template'); ?>
        </a>
        <a href="<?php echo $adminUrl; ?>/email-templates" class="btn btn-secondary">
            <i class="material-icons">arrow_back</i>
            <?php _e('back_to_templates'); ?>
        </a>
    </div>
</div>

<div class="admin-content-body">
    <div class="row">
        <!-- Template Content -->
        <div class="col-lg-8">
            <!-- Template Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">settings</i>
                        <?php _e('template_details'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="template-details">
                        <div class="detail-item">
                            <label><?php _e('template_key'); ?>:</label>
                            <div class="detail-value">
                                <code class="template-key"><?php echo htmlspecialchars($template['template_key']); ?></code>
                            </div>
                        </div>
                        
                        <div class="detail-item">
                            <label><?php _e('template_name'); ?>:</label>
                            <div class="detail-value"><?php echo htmlspecialchars($template['name']); ?></div>
                        </div>
                        
                        <?php if (!empty($template['description'])): ?>
                        <div class="detail-item">
                            <label><?php _e('description'); ?>:</label>
                            <div class="detail-value"><?php echo nl2br(htmlspecialchars($template['description'])); ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="detail-item">
                            <label><?php _e('status'); ?>:</label>
                            <div class="detail-value">
                                <span class="status-badge <?php echo $template['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $template['is_active'] ? __('active') : __('inactive'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Subject -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">subject</i>
                        <?php _e('email_subject'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="subject-content">
                        <code class="subject-code"><?php echo htmlspecialchars($template['subject']); ?></code>
                    </div>
                </div>
            </div>

            <!-- Email Body -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">description</i>
                        <?php _e('email_body'); ?>
                    </h5>
                    <div class="card-actions">
                        <button type="button" class="btn btn-sm btn-info" onclick="toggleCodeView()">
                            <i class="material-icons" id="code-toggle-icon">code</i>
                            <span id="code-toggle-text"><?php _e('view_code'); ?></span>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- HTML Preview -->
                    <div id="html-preview" class="body-preview">
                        <div class="preview-frame">
                            <iframe id="preview-iframe" frameborder="0"></iframe>
                        </div>
                    </div>
                    
                    <!-- Code View -->
                    <div id="code-view" class="body-code" style="display: none;">
                        <pre><code class="html-code"><?php echo htmlspecialchars($template['body']); ?></code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- Template Sidebar -->
        <div class="col-lg-4">
            <!-- Template Variables -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">code</i>
                        <?php _e('template_variables'); ?>
                    </h5>
                </div>
                <div class="card-body">
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
                            <?php _e('preview_with_sample_data'); ?>
                        </button>
                        <button type="button" class="btn btn-secondary btn-block" onclick="showTestModal()">
                            <i class="material-icons">send</i>
                            <?php _e('send_test_email'); ?>
                        </button>
                        <a href="<?php echo $adminUrl; ?>/email-templates/edit/<?php echo $template['id']; ?>" class="btn btn-warning btn-block">
                            <i class="material-icons">edit</i>
                            <?php _e('edit_template'); ?>
                        </a>
                        <a href="<?php echo $adminUrl; ?>/email-templates/toggle-status/<?php echo $template['id']; ?>" 
                           class="btn btn-block <?php echo $template['is_active'] ? 'btn-danger' : 'btn-success'; ?>">
                            <i class="material-icons"><?php echo $template['is_active'] ? 'visibility_off' : 'visibility'; ?></i>
                            <?php echo $template['is_active'] ? __('deactivate') : __('activate'); ?>
                        </a>
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
                            <strong><?php _e('variables_count'); ?>:</strong>
                            <span><?php echo count($variables); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Template -->
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="card-title mb-0">
                        <i class="material-icons">warning</i>
                        <?php _e('danger_zone'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted"><?php _e('delete_template_warning'); ?></p>
                    <a href="<?php echo $adminUrl; ?>/email-templates/delete/<?php echo $template['id']; ?>" 
                       class="btn btn-danger btn-block"
                       onclick="return confirm('<?php _e('confirm_delete_template'); ?>')">
                        <i class="material-icons">delete</i>
                        <?php _e('delete_template'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
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
                        <div id="preview-subject-content" class="preview-subject-box"></div>
                    </div>
                    <div class="preview-body">
                        <strong><?php _e('body'); ?>:</strong>
                        <div id="preview-body-content" class="preview-body-box"></div>
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
    // Load HTML preview in iframe
    loadHtmlPreview();
});

function loadHtmlPreview() {
    const iframe = document.getElementById('preview-iframe');
    const templateBody = <?php echo json_encode($template['body']); ?>;
    
    // Create a complete HTML document for the iframe
    const htmlContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Preview</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    margin: 0; 
                    padding: 20px; 
                    background: #f5f5f5;
                }
            </style>
        </head>
        <body>
            ${templateBody}
        </body>
        </html>
    `;
    
    iframe.srcdoc = htmlContent;
}

function toggleCodeView() {
    const htmlPreview = document.getElementById('html-preview');
    const codeView = document.getElementById('code-view');
    const icon = document.getElementById('code-toggle-icon');
    const text = document.getElementById('code-toggle-text');
    
    if (codeView.style.display === 'none') {
        htmlPreview.style.display = 'none';
        codeView.style.display = 'block';
        icon.textContent = 'visibility';
        text.textContent = '<?php _e('view_preview'); ?>';
    } else {
        htmlPreview.style.display = 'block';
        codeView.style.display = 'none';
        icon.textContent = 'code';
        text.textContent = '<?php _e('view_code'); ?>';
    }
}

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
            document.getElementById('preview-subject-content').textContent = data.subject;
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
.template-details {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.detail-item label {
    font-weight: 600;
    color: #666;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.detail-value {
    color: #333;
}

.template-key {
    background: #f8f9fa;
    padding: 4px 8px;
    border-radius: 4px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
}

.subject-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 4px;
    border: 1px solid #e9ecef;
}

.subject-code {
    font-family: 'Courier New', monospace;
    font-size: 14px;
    color: #495057;
    background: none;
    padding: 0;
}

.preview-frame {
    border: 1px solid #e9ecef;
    border-radius: 4px;
    overflow: hidden;
    background: white;
}

#preview-iframe {
    width: 100%;
    height: 400px;
    border: none;
}

.body-code {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 0;
}

.html-code {
    font-family: 'Courier New', monospace;
    font-size: 12px;
    line-height: 1.5;
    margin: 0;
    padding: 15px;
    background: none;
    color: #495057;
    white-space: pre-wrap;
    word-wrap: break-word;
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
}

.template-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.template-meta {
    display: flex;
    flex-direction: column;
    gap: 12px;
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

.card-actions {
    display: flex;
    align-items: center;
    gap: 10px;
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

.preview-subject-box {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 4px;
    border: 1px solid #e9ecef;
    font-weight: 500;
}

.preview-body-box {
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 20px;
    background: white;
    min-height: 300px;
    max-height: 500px;
    overflow-y: auto;
}

.form-help {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #666;
}
</style>