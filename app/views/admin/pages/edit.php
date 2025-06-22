<?php
/**
 * Admin Page Edit View - Improved with Rich Text Editor
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_page'); ?>: <?php echo htmlspecialchars($page['title']); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/pages" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_pages'); ?></span>
        </a>
        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/page/<?php echo $page['slug']; ?>" class="btn btn-light" target="_blank">
            <i class="material-icons">visibility</i>
            <span><?php _e('view_page'); ?></span>
        </a>
    </div>
</div>

<form action="<?php echo $adminUrl; ?>/pages/edit/<?php echo $page['id']; ?>" method="post" class="page-form">
    <input type="hidden" name="id" value="<?php echo $page['id']; ?>">
    
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('page_details'); ?></h3>
                </div>
                <div class="card-body">
                    <!-- Language Tabs -->
                    <div class="language-tabs">
                        <div class="language-tabs-nav">
                            <?php foreach ($languages as $lang): ?>
                                <button type="button" class="language-tab-btn <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                    <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['code']; ?>.png" alt="<?php echo $lang['name']; ?>">
                                    <span><?php echo $lang['name']; ?></span>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Language Tab Contents -->
                        <?php foreach ($languages as $lang): ?>
                            <?php $details = $pageDetails[$lang['id']] ?? []; ?>
                            <div class="language-tab-content <?php echo $lang['code'] === $currentLang ? 'active' : ''; ?>" data-lang="<?php echo $lang['code']; ?>">
                                <div class="form-group">
                                    <label for="title_<?php echo $lang['code']; ?>" class="form-label"><?php _e('title'); ?> <span class="required">*</span></label>
                                    <input type="text" id="title_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][title]" class="form-control" value="<?php echo htmlspecialchars($details['title'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="slug_<?php echo $lang['code']; ?>" class="form-label"><?php _e('slug'); ?></label>
                                    <div class="input-group">
                                        <input type="text" id="slug_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][slug]" class="form-control" value="<?php echo htmlspecialchars($details['slug'] ?? ''); ?>">
                                        <button type="button" class="btn btn-outline-secondary regenerate-slug" data-lang="<?php echo $lang['code']; ?>" title="<?php _e('regenerate_slug'); ?>">
                                            <i class="material-icons">refresh</i>
                                        </button>
                                    </div>
                                    <small class="form-text"><?php _e('slug_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="content_<?php echo $lang['code']; ?>" class="form-label"><?php _e('content'); ?></label>
                                    <div class="editor-container">
                                        <textarea id="content_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][content]" class="form-control rich-editor" rows="20"><?php echo htmlspecialchars($details['content'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label for="meta_title_<?php echo $lang['code']; ?>" class="form-label"><?php _e('meta_title'); ?></label>
                                    <input type="text" id="meta_title_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][meta_title]" class="form-control" value="<?php echo htmlspecialchars($details['meta_title'] ?? ''); ?>">
                                    <small class="form-text"><?php _e('meta_title_help'); ?></small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="meta_description_<?php echo $lang['code']; ?>" class="form-label"><?php _e('meta_description'); ?></label>
                                    <textarea id="meta_description_<?php echo $lang['code']; ?>" name="details[<?php echo $lang['id']; ?>][meta_description]" class="form-control" rows="3" maxlength="160"><?php echo htmlspecialchars($details['meta_description'] ?? ''); ?></textarea>
                                    <small class="form-text"><?php _e('meta_description_help'); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Page Options -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('page_options'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="template" class="form-label"><?php _e('template'); ?></label>
                        <select id="template" name="template" class="form-select">
                            <?php foreach ($templates as $key => $name): ?>
                                <option value="<?php echo $key; ?>" <?php echo $page['template'] === $key ? 'selected' : ''; ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text"><?php _e('template_help'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="order_number" class="form-label"><?php _e('order'); ?></label>
                        <input type="number" id="order_number" name="order_number" class="form-control" value="<?php echo $page['order_number']; ?>" min="0">
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $page['is_active'] ? 'checked' : ''; ?>>
                            <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Page Info -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('page_info'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="page-info">
                        <div class="info-item">
                            <div class="info-label"><?php _e('id'); ?></div>
                            <div class="info-value"><?php echo $page['id']; ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?php _e('created_at'); ?></div>
                            <div class="info-value"><?php echo date('d M Y, H:i', strtotime($page['created_at'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?php _e('updated_at'); ?></div>
                            <div class="info-value"><?php echo date('d M Y, H:i', strtotime($page['updated_at'])); ?></div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><?php _e('status'); ?></div>
                            <div class="info-value">
                                <span class="status-badge status-<?php echo $page['is_active'] ? 'active' : 'inactive'; ?>">
                                    <?php echo $page['is_active'] ? __('active') : __('inactive'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Editor Help -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php _e('editor_help'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="editor-help">
                        <h5><?php _e('formatting_options'); ?>:</h5>
                        <ul>
                            <li><?php _e('bold_italic_underline'); ?></li>
                            <li><?php _e('headings_lists'); ?></li>
                            <li><?php _e('links_images'); ?></li>
                            <li><?php _e('tables_quotes'); ?></li>
                            <li><?php _e('text_alignment'); ?></li>
                        </ul>
                        <small class="text-muted"><?php _e('editor_keyboard_shortcuts'); ?></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">
            <i class="material-icons">save</i>
            <?php _e('save_changes'); ?>
        </button>
        <a href="<?php echo $adminUrl; ?>/pages" class="btn btn-light">
            <i class="material-icons">cancel</i>
            <?php _e('cancel'); ?>
        </a>
        
        <a href="<?php echo $adminUrl; ?>/pages/delete/<?php echo $page['id']; ?>" class="btn btn-danger ms-auto delete-btn" data-confirm="<?php _e('delete_page_confirm'); ?>">
            <i class="material-icons">delete</i>
            <?php _e('delete_page'); ?>
        </a>
    </div>
</form>

<!-- TinyMCE CSS - No external CSS needed -->

<style>
.language-tabs {
    margin-bottom: var(--spacing-lg);
}

.language-tabs-nav {
    display: flex;
    border-bottom: 1px solid var(--gray-300);
    margin-bottom: var(--spacing-md);
    overflow-x: auto;
}

.language-tab-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border: none;
    background: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    white-space: nowrap;
    flex-shrink: 0;
}

.language-tab-btn:hover {
    background-color: var(--gray-50);
}

.language-tab-btn.active {
    border-bottom-color: var(--primary-color);
    color: var(--primary-color);
    background-color: var(--gray-50);
}

.language-tab-btn img {
    width: 20px;
    height: 15px;
    object-fit: cover;
    border-radius: 2px;
}

.language-tab-content {
    display: none;
}

.language-tab-content.active {
    display: block;
}

.editor-container {
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius);
    overflow: hidden;
}

.rich-editor {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    min-height: 400px;
}

.ck-editor__editable {
    min-height: 400px;
    padding: 1rem;
}

.ck-toolbar {
    border-bottom: 1px solid var(--gray-300) !important;
}

.page-info {
    display: grid;
    gap: 0.5rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--gray-200);
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    color: var(--gray-600);
    font-weight: var(--font-weight-medium);
}

.info-value {
    font-weight: var(--font-weight-semibold);
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

.form-actions {
    margin-top: var(--spacing-lg);
    display: flex;
    gap: var(--spacing-md);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--gray-200);
}

.ms-auto {
    margin-left: auto;
}

.editor-help {
    font-size: var(--font-size-sm);
}

.editor-help h5 {
    margin-bottom: 0.5rem;
    color: var(--gray-700);
}

.editor-help ul {
    margin-bottom: 1rem;
    padding-left: 1.25rem;
}

.editor-help li {
    margin-bottom: 0.25rem;
    color: var(--gray-600);
}

.required {
    color: var(--danger-color);
}

.form-text {
    font-size: var(--font-size-xs);
    color: var(--gray-600);
    margin-top: 0.25rem;
}

.char-counter {
    float: right;
}

.input-group {
    display: flex;
}

.input-group .form-control {
    flex: 1;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 0;
}

.regenerate-slug {
    padding: 0.375rem 0.75rem;
}

/* Loading state for editors */
.editor-loading {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 200px;
    background-color: var(--gray-50);
    border-radius: var(--border-radius);
}

.editor-loading::after {
    content: "Editör yükleniyor...";
    color: var(--gray-600);
}
</style>

<!-- TinyMCE Script -->
<script src="https://cdn.tiny.cloud/1/ufsmxd4yxjc0lp4ilt4e2s3865ezo6rpe06ln8dxgcuj0hms/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let editors = {};
    
    // Language Tabs
    const langTabBtns = document.querySelectorAll('.language-tab-btn');
    const langTabContents = document.querySelectorAll('.language-tab-content');
    
    langTabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const lang = this.dataset.lang;
            
            // Deactivate all tabs
            langTabBtns.forEach(btn => btn.classList.remove('active'));
            langTabContents.forEach(content => content.classList.remove('active'));
            
            // Activate selected tab
            this.classList.add('active');
            document.querySelector(`.language-tab-content[data-lang="${lang}"]`).classList.add('active');
        });
    });
    
    // Regenerate slug buttons
    const regenerateSlugBtns = document.querySelectorAll('.regenerate-slug');
    regenerateSlugBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const lang = this.dataset.lang;
            const titleInput = document.getElementById(`title_${lang}`);
            const slugInput = document.getElementById(`slug_${lang}`);
            
            if (titleInput && slugInput && titleInput.value) {
                const slug = generateSlug(titleInput.value);
                slugInput.value = slug;
            }
        });
    });
    
    // Initialize TinyMCE editors
    const richEditors = document.querySelectorAll('.rich-editor');
    
    richEditors.forEach(textarea => {
        const container = textarea.closest('.editor-container');
        container.classList.add('editor-loading');
        
        tinymce.init({
            target: textarea,
            height: 400,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons',
                'template', 'codesample', 'hr', 'pagebreak', 'nonbreaking',
                'toc', 'imagetools', 'textpattern', 'noneditable', 'quickbars'
            ],
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ],
            ai_request: (request, respondWith) => respondWith.string(() => Promise.reject("See docs to implement AI Assistant")),
            
            // Content styling
            content_style: `
                body { 
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6; 
                }
                h1 { font-size: 2rem; }
                h2 { font-size: 1.5rem; }
                h3 { font-size: 1.25rem; }
                h4 { font-size: 1.1rem; }
                p { margin-bottom: 1rem; }
                img { max-width: 100%; height: auto; }
                table { border-collapse: collapse; width: 100%; }
                table td, table th { border: 1px solid #ddd; padding: 8px; }
                table th { background-color: #f2f2f2; }
                blockquote { 
                    border-left: 4px solid #ccc; 
                    margin: 1rem 0; 
                    padding-left: 1rem; 
                    font-style: italic; 
                }
                code { 
                    background-color: #f4f4f4; 
                    padding: 2px 4px; 
                    border-radius: 3px; 
                    font-family: 'Courier New', monospace; 
                }
                pre { 
                    background-color: #f4f4f4; 
                    padding: 1rem; 
                    border-radius: 5px; 
                    overflow-x: auto; 
                }
            `,
            
            // Image handling
            image_advtab: true,
            image_caption: true,
            image_title: true,
            
            // Link handling
            link_assume_external_targets: true,
            link_context_toolbar: true,
            
            // Table options
            table_use_colgroups: true,
            table_sizing_mode: 'responsive',
            table_column_resizing: true,
            table_resize_bars: true,
            
            // Advanced options
            contextmenu: 'link image table',
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            quickbars_insert_toolbar: 'quickimage quicktable',
            toolbar_mode: 'sliding',
            
            // Paste options
            paste_as_text: false,
            paste_auto_cleanup_on_paste: true,
            
            // Setup callback
            setup: function (editor) {
                editor.on('init', function () {
                    container.classList.remove('editor-loading');
                    editors[textarea.id] = editor;
                });
                
                editor.on('change', function () {
                    editor.save();
                });
            }
        });
    });
    
    // Form submission - sync editor data
    const form = document.querySelector('.page-form');
    if (form) {
        form.addEventListener('submit', function() {
            // TinyMCE automatically syncs data with textarea
            tinymce.triggerSave();
        });
    }
    
    // Meta description character counter
    const metaDescInputs = document.querySelectorAll('[id^="meta_description_"]');
    metaDescInputs.forEach(textarea => {
        const maxLength = parseInt(textarea.getAttribute('maxlength')) || 160;
        const counter = document.createElement('small');
        counter.className = 'form-text char-counter';
        textarea.parentNode.appendChild(counter);
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${textarea.value.length}/${maxLength} <?php _e("characters"); ?>`;
            counter.style.color = remaining < 20 ? 'var(--danger-color)' : 'var(--gray-600)';
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
    
    // Delete confirmation
    const deleteBtn = document.querySelector('.delete-btn');
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmMessage = this.dataset.confirm || '<?php _e("delete_confirm"); ?>';
            
            if (confirm(confirmMessage)) {
                window.location.href = this.getAttribute('href');
            }
        });
    }
    
    // Utility function to generate slug
    function generateSlug(text) {
        return text
            .toLowerCase()
            .trim()
            .replace(/[\s\W-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }
});
</script>