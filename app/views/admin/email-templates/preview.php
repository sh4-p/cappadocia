<!-- Email Templates Preview View - app/views/admin/email-templates/preview.php -->

<div class="admin-content-header">
    <div class="content-header-left">
        <h1 class="content-header-title">
            <i class="material-icons">preview</i>
            <?php _e('preview_email_template'); ?>
        </h1>
        <p class="content-header-subtitle"><?php echo htmlspecialchars($template['name']); ?></p>
    </div>
    <div class="content-header-right">
        <a href="<?php echo $adminUrl; ?>/email-templates/edit/<?php echo $template['id']; ?>" class="btn btn-warning">
            <i class="material-icons">edit</i>
            <?php _e('edit_template'); ?>
        </a>
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
    <div class="row">
        <!-- Email Preview -->
        <div class="col-lg-8">
            <!-- Email Subject Preview -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">subject</i>
                        <?php _e('email_subject_preview'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="subject-preview">
                        <div class="email-header">
                            <div class="email-from">
                                <strong><?php _e('from'); ?>:</strong> 
                                <span><?php echo htmlspecialchars($sampleData['from_name'] ?? $sampleData['from_email']); ?> &lt;<?php echo htmlspecialchars($sampleData['from_email']); ?>&gt;</span>
                            </div>
                            <div class="email-subject">
                                <strong><?php _e('subject'); ?>:</strong> 
                                <span class="subject-text"><?php echo htmlspecialchars($renderedSubject); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Email Body Preview -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title-row">
                        <h5 class="card-title">
                            <i class="material-icons">email</i>
                            <?php _e('email_body_preview'); ?>
                        </h5>
                        <div class="preview-actions">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="togglePreviewMode()">
                                <i class="material-icons" id="preview-mode-icon">phone_android</i>
                                <span id="preview-mode-text"><?php _e('mobile_view'); ?></span>
                            </button>
                            <button type="button" class="btn btn-sm btn-info" onclick="refreshPreview()">
                                <i class="material-icons">refresh</i>
                                <?php _e('refresh'); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="email-preview-container">
                        <!-- Desktop View -->
                        <div id="desktop-preview" class="preview-frame desktop-frame">
                            <div class="preview-toolbar">
                                <div class="preview-title">
                                    <i class="material-icons">desktop_windows</i>
                                    <?php _e('desktop_view'); ?>
                                </div>
                                <div class="preview-actions-small">
                                    <button type="button" class="btn btn-xs btn-outline-secondary" onclick="openInNewWindow()">
                                        <i class="material-icons">open_in_new</i>
                                    </button>
                                </div>
                            </div>
                            <iframe id="desktop-iframe" class="preview-iframe" frameborder="0"></iframe>
                        </div>

                        <!-- Mobile View -->
                        <div id="mobile-preview" class="preview-frame mobile-frame" style="display: none;">
                            <div class="preview-toolbar">
                                <div class="preview-title">
                                    <i class="material-icons">phone_android</i>
                                    <?php _e('mobile_view'); ?>
                                </div>
                                <div class="preview-actions-small">
                                    <button type="button" class="btn btn-xs btn-outline-secondary" onclick="openInNewWindow()">
                                        <i class="material-icons">open_in_new</i>
                                    </button>
                                </div>
                            </div>
                            <iframe id="mobile-iframe" class="preview-iframe mobile-iframe" frameborder="0"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Preview Info Sidebar -->
        <div class="col-lg-4">
            <!-- Template Information -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">info</i>
                        <?php _e('template_information'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="template-info">
                        <div class="info-item">
                            <label><?php _e('template_key'); ?>:</label>
                            <div class="info-value">
                                <code><?php echo htmlspecialchars($template['template_key']); ?></code>
                            </div>
                        </div>
                        <div class="info-item">
                            <label><?php _e('template_name'); ?>:</label>
                            <div class="info-value"><?php echo htmlspecialchars($template['name']); ?></div>
                        </div>
                        <div class="info-item">
                            <label><?php _e('status'); ?>:</label>
                            <div class="info-value">
                                <span class="status-badge <?php echo $template['is_active'] ? 'status-active' : 'status-inactive'; ?>">
                                    <?php echo $template['is_active'] ? __('active') : __('inactive'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sample Data Used -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">data_object</i>
                        <?php _e('sample_data'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="sample-data">
                        <p class="text-muted mb-3"><?php _e('sample_data_desc'); ?></p>
                        <div class="data-list">
                            <?php foreach ($sampleData as $key => $value): ?>
                                <div class="data-item">
                                    <div class="data-key">
                                        <code>{{<?php echo htmlspecialchars($key); ?>}}</code>
                                    </div>
                                    <div class="data-value">
                                        <?php echo htmlspecialchars(is_array($value) ? json_encode($value) : $value); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Preview Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">play_arrow</i>
                        <?php _e('preview_actions'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="preview-actions-list">
                        <button type="button" class="btn btn-primary btn-block" onclick="showTestModal()">
                            <i class="material-icons">send</i>
                            <?php _e('send_test_email'); ?>
                        </button>
                        <button type="button" class="btn btn-secondary btn-block" onclick="copyPreviewHTML()">
                            <i class="material-icons">content_copy</i>
                            <?php _e('copy_html'); ?>
                        </button>
                        <button type="button" class="btn btn-info btn-block" onclick="downloadHTML()">
                            <i class="material-icons">download</i>
                            <?php _e('download_html'); ?>
                        </button>
                        <a href="<?php echo $adminUrl; ?>/email-templates/edit/<?php echo $template['id']; ?>" class="btn btn-warning btn-block">
                            <i class="material-icons">edit</i>
                            <?php _e('edit_template'); ?>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Template Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">
                        <i class="material-icons">analytics</i>
                        <?php _e('template_stats'); ?>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="stats-list">
                        <div class="stat-item">
                            <div class="stat-label"><?php _e('created_at'); ?>:</div>
                            <div class="stat-value"><?php echo date('M j, Y H:i', strtotime($template['created_at'])); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label"><?php _e('last_updated'); ?>:</div>
                            <div class="stat-value"><?php echo date('M j, Y H:i', strtotime($template['updated_at'])); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label"><?php _e('variables_count'); ?>:</div>
                            <div class="stat-value"><?php echo count(json_decode($template['variables'], true) ?: []); ?></div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-label"><?php _e('body_length'); ?>:</div>
                            <div class="stat-value"><?php echo number_format(strlen($template['body'])); ?> <?php _e('characters'); ?></div>
                        </div>
                    </div>
                </div>
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
                <button type="button" class="close" onclick="closeTestModal()">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?php echo $adminUrl; ?>/email-templates/test-send/<?php echo $template['id']; ?>" method="POST" id="testEmailForm">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="test_email" class="form-label required">
                            <?php _e('test_email_address'); ?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="test_email" id="test_email" class="form-control" 
                               placeholder="<?php _e('enter_test_email'); ?>" required>
                        <small class="form-help"><?php _e('test_email_preview_help'); ?></small>
                    </div>
                    <div class="alert alert-info">
                        <i class="material-icons">info</i>
                        <?php _e('test_email_sample_data_info'); ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeTestModal()"><?php _e('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary" id="sendTestBtn">
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
    loadEmailPreview();
});

const emailBody = <?php echo json_encode($renderedBody); ?>;
const emailSubject = <?php echo json_encode($renderedSubject); ?>;

function loadEmailPreview() {
    const desktopIframe = document.getElementById('desktop-iframe');
    const mobileIframe = document.getElementById('mobile-iframe');
    
    const htmlContent = createEmailHTML();
    
    desktopIframe.srcdoc = htmlContent;
    mobileIframe.srcdoc = htmlContent;
}

function createEmailHTML() {
    return `
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>${emailSubject}</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    line-height: 1.6; 
                    margin: 0; 
                    padding: 20px; 
                    background: #f5f5f5;
                    color: #333;
                }
                .email-container {
                    max-width: 600px;
                    margin: 0 auto;
                    background: white;
                    border-radius: 8px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    overflow: hidden;
                }
                @media (max-width: 600px) {
                    body { padding: 10px; }
                    .email-container { border-radius: 0; }
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                ${emailBody}
            </div>
        </body>
        </html>
    `;
}

function togglePreviewMode() {
    const desktopPreview = document.getElementById('desktop-preview');
    const mobilePreview = document.getElementById('mobile-preview');
    const icon = document.getElementById('preview-mode-icon');
    const text = document.getElementById('preview-mode-text');
    
    if (mobilePreview.style.display === 'none') {
        desktopPreview.style.display = 'none';
        mobilePreview.style.display = 'block';
        icon.textContent = 'desktop_windows';
        text.textContent = '<?php _e('desktop_view'); ?>';
    } else {
        desktopPreview.style.display = 'block';
        mobilePreview.style.display = 'none';
        icon.textContent = 'phone_android';
        text.textContent = '<?php _e('mobile_view'); ?>';
    }
}

function refreshPreview() {
    loadEmailPreview();
}

function openInNewWindow() {
    const htmlContent = createEmailHTML();
    const newWindow = window.open('', '_blank', 'width=800,height=600');
    newWindow.document.write(htmlContent);
    newWindow.document.close();
}

// Form submit handler - EKLE
document.getElementById('testEmailForm').addEventListener('submit', function(e) {
    const email = document.getElementById('test_email').value;
    const btn = document.getElementById('sendTestBtn');
    
    if (!email) {
        alert('<?php _e('please_enter_email_address'); ?>');
        e.preventDefault();
        return false;
    }
    
    // Disable button to prevent double submission
    btn.disabled = true;
    btn.innerHTML = '<i class="material-icons">hourglass_empty</i> <?php _e('sending'); ?>...';
    
    return true;
});

// Close modal when clicking outside - EKLE
document.getElementById('testModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTestModal();
    }
});

// ESC key handler - EKLE
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeTestModal();
    }
});

// MEVCUT showTestModal FONKSIYONUNU DEĞİŞTİR:
function showTestModal() {
    document.getElementById('testModal').style.display = 'block';
    document.getElementById('testModal').classList.add('show');
    document.body.style.overflow = 'hidden'; // Prevent background scroll
}

// YENİ FONKSIYON EKLE:
function closeTestModal() {
    const modal = document.getElementById('testModal');
    const btn = document.getElementById('sendTestBtn');
    
    // Reset modal state
    modal.style.display = 'none';
    modal.classList.remove('show');
    document.body.style.overflow = 'auto';
    
    // Reset form
    document.getElementById('test_email').value = '';
    btn.disabled = false;
    btn.innerHTML = '<i class="material-icons">send</i> <?php _e('send_test'); ?>';
}

function copyPreviewHTML() {
    const htmlContent = createEmailHTML();
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(htmlContent).then(function() {
            alert('<?php _e('html_copied_to_clipboard'); ?>');
        }).catch(function() {
            fallbackCopyText(htmlContent);
        });
    } else {
        fallbackCopyText(htmlContent);
    }
}

function fallbackCopyText(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        document.execCommand('copy');
        alert('<?php _e('html_copied_to_clipboard'); ?>');
    } catch (err) {
        alert('<?php _e('copy_failed'); ?>');
    }
    
    document.body.removeChild(textArea);
}

function downloadHTML() {
    const htmlContent = createEmailHTML();
    const blob = new Blob([htmlContent], { type: 'text/html' });
    const url = URL.createObjectURL(blob);
    
    const a = document.createElement('a');
    a.href = url;
    a.download = 'email-template-<?php echo $template['template_key']; ?>.html';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    URL.revokeObjectURL(url);
}
</script>

<style>
/* Modal Fixes */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    backdrop-filter: blur(2px);
}

.modal.show {
    display: block;
}

.modal-dialog {
    margin: 50px auto;
    max-width: 500px;
    position: relative;
    top: 50%;
    transform: translateY(-50%);
}

.modal-content {
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.3);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-title {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
}

.close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background-color 0.2s;
}

.close:hover {
    background-color: #f8f9fa;
}

.modal-body {
    padding: 20px;
}

.modal-footer {
    padding: 20px;
    border-top: 1px solid #e9ecef;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
}

.form-control {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #007bff;
    color: white;
}

.btn-primary:hover:not(:disabled) {
    background: #0056b3;
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #545b62;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.alert {
    padding: 12px;
    border-radius: 4px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 0;
}

.alert-info {
    background: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.alert i {
    font-size: 18px;
    margin-top: -2px;
}

.text-danger {
    color: #dc3545;
}

.form-help {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #666;
}

/* Responsive */
@media (max-width: 600px) {
    .modal-dialog {
        margin: 20px;
        max-width: none;
    }
    
    .modal-content {
        border-radius: 6px;
    }
}
.card-title-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.preview-actions {
    display: flex;
    gap: 8px;
}

.subject-preview {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    padding: 15px;
}

.email-header {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.email-from,
.email-subject {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 14px;
}

.subject-text {
    font-weight: 600;
    color: #333;
}

.email-preview-container {
    position: relative;
}

.preview-frame {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.desktop-frame {
    width: 100%;
}

.mobile-frame {
    width: 375px;
    margin: 0 auto;
}

.preview-toolbar {
    background: #f8f9fa;
    border-bottom: 1px solid #e0e0e0;
    padding: 8px 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.preview-title {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    font-weight: 500;
    color: #666;
}

.preview-title i {
    font-size: 16px;
}

.preview-actions-small {
    display: flex;
    gap: 4px;
}

.preview-iframe {
    width: 100%;
    height: 500px;
    border: none;
}

.mobile-iframe {
    width: 375px;
    height: 600px;
}

.template-info,
.sample-data,
.stats-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-item,
.stat-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label,
.stat-label {
    font-weight: 600;
    color: #666;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
}

.info-value,
.stat-value {
    color: #333;
    font-size: 14px;
}

.data-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
    max-height: 300px;
    overflow-y: auto;
}

.data-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 4px;
}

.data-key {
    font-size: 11px;
}

.data-key code {
    background: #e3f2fd;
    color: #1976d2;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Courier New', monospace;
}

.data-value {
    font-size: 12px;
    color: #666;
    word-break: break-all;
}

.preview-actions-list {
    display: flex;
    flex-direction: column;
    gap: 10px;
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

.form-help {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #666;
}

.alert {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px;
    border-radius: 6px;
    font-size: 13px;
}

.alert-info {
    background: #e3f2fd;
    color: #1976d2;
    border: 1px solid #bbdefb;
}

.alert i {
    font-size: 18px;
    margin-top: -2px;
}

@media (max-width: 768px) {
    .mobile-frame {
        width: 100%;
    }
    
    .mobile-iframe {
        width: 100%;
    }
    
    .card-title-row {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .preview-actions {
        align-self: flex-end;
    }
}
</style>