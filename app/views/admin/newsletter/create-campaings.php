<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">add</i> <?php _e('create_campaign'); ?></h1>
        <p><?php _e('create_new_newsletter_campaign'); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/newsletter/campaigns" class="btn btn-outline-secondary">
            <i class="material-icons">arrow_back</i>
            <?php _e('back_to_campaigns'); ?>
        </a>
    </div>
</div>

<form method="POST" action="<?php echo $adminUrl; ?>/newsletter/create-campaign" id="campaign-form">
    <div class="row">
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-header">
                    <h4><?php _e('campaign_information'); ?></h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="subject" class="form-label"><?php _e('email_subject'); ?> <span class="required">*</span></label>
                        <input type="text" name="subject" id="subject" class="form-control" 
                               value="<?php echo htmlspecialchars($subject ?? ''); ?>" required
                               placeholder="<?php _e('enter_email_subject'); ?>">
                        <small class="form-text text-muted"><?php _e('email_subject_help'); ?></small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label"><?php _e('email_content'); ?> <span class="required">*</span></label>
                        <textarea name="content" id="content" class="form-control" rows="15" required
                                  placeholder="<?php _e('enter_email_content'); ?>"><?php echo htmlspecialchars($content ?? ''); ?></textarea>
                        <small class="form-text text-muted"><?php _e('email_content_help'); ?></small>
                    </div>
                </div>
            </div>
            
            <!-- Multi-language Content (if multiple languages exist) -->
            <?php if (count($languages) > 1): ?>
            <div class="card mt-4">
                <div class="card-header">
                    <h4><?php _e('multilingual_content'); ?></h4>
                    <small class="text-muted"><?php _e('multilingual_content_description'); ?></small>
                </div>
                <div class="card-body">
                    <div class="language-tabs">
                        <ul class="nav nav-tabs" role="tablist">
                            <?php foreach ($languages as $index => $language): ?>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link <?php echo $index === 0 ? 'active' : ''; ?>" 
                                        id="lang-<?php echo $language['code']; ?>-tab" 
                                        data-bs-toggle="tab" 
                                        data-bs-target="#lang-<?php echo $language['code']; ?>" 
                                        type="button" role="tab">
                                    <?php if (file_exists(BASE_PATH . '/public/uploads/flags/' . $language['code'] . '.png')): ?>
                                        <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $language['code']; ?>.png" 
                                             alt="<?php echo $language['code']; ?>" width="20" height="15">
                                    <?php endif; ?>
                                    <?php echo htmlspecialchars($language['name']); ?>
                                </button>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        
                        <div class="tab-content mt-3">
                            <?php foreach ($languages as $index => $language): ?>
                            <div class="tab-pane fade <?php echo $index === 0 ? 'show active' : ''; ?>" 
                                 id="lang-<?php echo $language['code']; ?>" role="tabpanel">
                                
                                <div class="mb-3">
                                    <label for="details[<?php echo $language['id']; ?>][subject]" class="form-label">
                                        <?php _e('email_subject'); ?> (<?php echo $language['name']; ?>)
                                    </label>
                                    <input type="text" 
                                           name="details[<?php echo $language['id']; ?>][subject]" 
                                           id="details_<?php echo $language['id']; ?>_subject"
                                           class="form-control" 
                                           value="<?php echo htmlspecialchars($details[$language['id']]['subject'] ?? ''); ?>"
                                           placeholder="<?php _e('enter_subject_for'); ?> <?php echo $language['name']; ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="details[<?php echo $language['id']; ?>][content]" class="form-label">
                                        <?php _e('email_content'); ?> (<?php echo $language['name']; ?>)
                                    </label>
                                    <textarea name="details[<?php echo $language['id']; ?>][content]" 
                                              id="details_<?php echo $language['id']; ?>_content"
                                              class="form-control" rows="12"
                                              placeholder="<?php _e('enter_content_for'); ?> <?php echo $language['name']; ?>"><?php echo htmlspecialchars($details[$language['id']]['content'] ?? ''); ?></textarea>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="col-md-4">
            <!-- Campaign Settings -->
            <div class="card">
                <div class="card-header">
                    <h5><i class="material-icons">settings</i> <?php _e('campaign_settings'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label"><?php _e('campaign_status'); ?></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="status" id="status_draft" value="draft" checked>
                            <label class="form-check-label" for="status_draft">
                                <span class="badge badge-secondary"><?php _e('draft'); ?></span>
                                <small class="d-block text-muted"><?php _e('save_as_draft_description'); ?></small>
                            </label>
                        </div>
                    </div>
                    
                    <div class="alert alert-info">
                        <h6><i class="material-icons">info</i> <?php _e('about_drafts'); ?></h6>
                        <p class="small mb-0"><?php _e('draft_campaign_explanation'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Preview -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="material-icons">preview</i> <?php _e('preview'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="preview-container">
                        <div class="email-preview">
                            <div class="email-header">
                                <strong id="preview-subject"><?php _e('email_subject_preview'); ?></strong>
                            </div>
                            <div class="email-content" id="preview-content">
                                <p class="text-muted"><?php _e('email_content_preview'); ?></p>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="updatePreview()">
                        <i class="material-icons">refresh</i>
                        <?php _e('update_preview'); ?>
                    </button>
                </div>
            </div>
            
            <!-- Recipients Info -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="material-icons">people</i> <?php _e('recipients'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="recipient-stats">
                        <div class="stat-item">
                            <span class="stat-number" id="active-subscribers">-</span>
                            <span class="stat-label"><?php _e('active_subscribers'); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number" id="total-subscribers">-</span>
                            <span class="stat-label"><?php _e('total_subscribers'); ?></span>
                        </div>
                    </div>
                    <small class="text-muted"><?php _e('campaign_will_be_sent_to_active'); ?></small>
                </div>
            </div>
            
            <!-- Available Variables -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5><i class="material-icons">code</i> <?php _e('available_variables'); ?></h5>
                </div>
                <div class="card-body">
                    <div class="variables-list">
                        <div class="variable-item">
                            <code>{{email}}</code>
                            <small><?php _e('subscriber_email'); ?></small>
                        </div>
                        <div class="variable-item">
                            <code>{{name}}</code>
                            <small><?php _e('subscriber_name'); ?></small>
                        </div>
                        <div class="variable-item">
                            <code>{{unsubscribe_link}}</code>
                            <small><?php _e('unsubscribe_link'); ?></small>
                        </div>
                        <div class="variable-item">
                            <code>{{campaign_subject}}</code>
                            <small><?php _e('campaign_subject'); ?></small>
                        </div>
                        <div class="variable-item">
                            <code>{{site_name}}</code>
                            <small><?php _e('website_name'); ?></small>
                        </div>
                    </div>
                    <small class="text-muted"><?php _e('click_to_insert_variable'); ?></small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Form Actions -->
    <div class="form-actions mt-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="material-icons">save</i>
                            <?php _e('save_campaign'); ?>
                        </button>
                        <a href="<?php echo $adminUrl; ?>/newsletter/campaigns" class="btn btn-secondary">
                            <?php _e('cancel'); ?>
                        </a>
                    </div>
                    <div class="text-muted">
                        <small><?php _e('campaign_save_notice'); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.required {
    color: #dc3545;
}

.email-preview {
    border: 1px solid #e9ecef;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.email-header {
    background: #f8f9fa;
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
    font-size: 16px;
}

.email-content {
    padding: 15px;
    min-height: 120px;
    font-size: 14px;
    line-height: 1.5;
}

.recipient-stats {
    display: flex;
    justify-content: space-around;
    margin-bottom: 15px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 24px;
    font-weight: 600;
    color: #4361ee;
}

.stat-label {
    display: block;
    font-size: 12px;
    color: #666;
}

.variables-list {
    max-height: 200px;
    overflow-y: auto;
}

.variable-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    border-bottom: 1px solid #f1f1f1;
    cursor: pointer;
}

.variable-item:hover {
    background-color: #f8f9fa;
}

.variable-item:last-child {
    border-bottom: none;
}

.variable-item code {
    background: #e9ecef;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
}

.variable-item small {
    color: #666;
    font-size: 11px;
}

.language-tabs .nav-tabs {
    border-bottom: 1px solid #dee2e6;
}

.language-tabs .nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
}

.language-tabs .nav-link img {
    border-radius: 2px;
}

.form-actions .card {
    border: 2px dashed #e9ecef;
    background: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load subscriber stats
    loadSubscriberStats();
    
    // Update preview when content changes
    const subjectInput = document.getElementById('subject');
    const contentTextarea = document.getElementById('content');
    
    if (subjectInput) {
        subjectInput.addEventListener('input', updatePreview);
    }
    
    if (contentTextarea) {
        contentTextarea.addEventListener('input', updatePreview);
    }
    
    // Variable insertion
    const variableItems = document.querySelectorAll('.variable-item');
    variableItems.forEach(item => {
        item.addEventListener('click', function() {
            const code = this.querySelector('code').textContent;
            insertAtCursor(contentTextarea, code);
            updatePreview();
        });
    });
    
    // Initialize preview
    updatePreview();
});

function loadSubscriberStats() {
    // In a real implementation, this would be an AJAX call
    // For now, we'll use placeholder values
    document.getElementById('active-subscribers').textContent = '<?php echo $stats["active"] ?? 0; ?>';
    document.getElementById('total-subscribers').textContent = '<?php echo $stats["total"] ?? 0; ?>';
}

function updatePreview() {
    const subject = document.getElementById('subject').value || '<?php _e("email_subject_preview"); ?>';
    const content = document.getElementById('content').value || '<?php _e("email_content_preview"); ?>';
    
    document.getElementById('preview-subject').textContent = subject;
    document.getElementById('preview-content').innerHTML = content.replace(/\n/g, '<br>');
}

function insertAtCursor(textarea, text) {
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const value = textarea.value;
    
    textarea.value = value.substring(0, start) + text + value.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + text.length;
    textarea.focus();
}

// Form validation
document.getElementById('campaign-form').addEventListener('submit', function(e) {
    const subject = document.getElementById('subject').value.trim();
    const content = document.getElementById('content').value.trim();
    
    if (!subject) {
        e.preventDefault();
        alert('<?php _e("please_enter_subject"); ?>');
        document.getElementById('subject').focus();
        return;
    }
    
    if (!content) {
        e.preventDefault();
        alert('<?php _e("please_enter_content"); ?>');
        document.getElementById('content').focus();
        return;
    }
    
    if (content.indexOf('{{unsubscribe_link}}') === -1) {
        if (!confirm('<?php _e("no_unsubscribe_link_warning"); ?>')) {
            e.preventDefault();
            return;
        }
    }
});
</script>