<?php
/**
 * Admin Testimonial Edit View
 */
?>

<!-- Required JS Libraries -->
<script src="https://cdn.ckeditor.com/ckeditor5/38.0.1/classic/ckeditor.js"></script>

<div class="page-header">
    <h1 class="page-title"><?php _e('edit_testimonial'); ?>: <?php echo $testimonial['name']; ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/testimonials" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_testimonials'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form action="<?php echo $adminUrl; ?>/testimonials/edit/<?php echo $testimonial['id']; ?>" method="post" enctype="multipart/form-data" id="testimonial-form">
            <input type="hidden" name="id" value="<?php echo $testimonial['id']; ?>">
            
            <div class="row">
                <div class="col-md-8">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3 class="form-section-title"><?php _e('basic_information'); ?></h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label"><?php _e('name'); ?> <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($testimonial['name']); ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="position" class="form-label"><?php _e('position'); ?></label>
                                    <input type="text" id="position" name="position" class="form-control" value="<?php echo htmlspecialchars($testimonial['position']); ?>">
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="rating" class="form-label"><?php _e('rating'); ?> <span class="required">*</span></label>
                            <div class="rating-input">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="rating-<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php echo $testimonial['rating'] == $i ? 'checked' : ''; ?>>
                                    <label for="rating-<?php echo $i; ?>"><i class="material-icons">star</i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Content Tabs -->
                    <div class="form-section">
                        <h3 class="form-section-title"><?php _e('content'); ?> <span class="required">*</span></h3>
                        
                        <!-- Plain textarea version - fallback solution -->
                        <?php foreach ($languages as $language): ?>
                            <div class="form-group mb-4">
                                <label class="form-label">
                                    <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $language['flag']; ?>" alt="<?php echo $language['name']; ?>" width="20" class="mr-2">
                                    <?php echo $language['name']; ?>
                                </label>
                                <textarea 
                                    name="details[<?php echo $language['id']; ?>][content]" 
                                    class="form-control" 
                                    rows="5"
                                    style="min-height: 150px;"><?php echo isset($testimonialDetails[$language['id']]['content']) ? htmlspecialchars($testimonialDetails[$language['id']]['content']) : ''; ?></textarea>
                            </div>
                        <?php endforeach; ?>
                    </div>
                
                <div class="col-md-4">
                    <!-- Sidebar -->
                    <div class="form-sidebar">
                        <!-- Image Upload -->
                        <div class="form-sidebar-section">
                            <h4 class="form-sidebar-title"><?php _e('image'); ?></h4>
                            
                            <div class="image-preview-container">
                                <div class="image-preview" id="image-preview">
                                    <?php if ($testimonial['image']): ?>
                                        <img src="<?php echo $uploadsUrl; ?>/testimonials/<?php echo $testimonial['image']; ?>" alt="<?php echo $testimonial['name']; ?>">
                                    <?php else: ?>
                                        <i class="material-icons">person</i>
                                    <?php endif; ?>
                                </div>
                                
                                <input type="file" id="image" name="image" class="form-control-file" accept="image/*">
                                <button type="button" class="btn btn-light btn-sm mt-2" id="remove-image" <?php echo $testimonial['image'] ? '' : 'style="display: none;"'; ?>>
                                    <i class="material-icons">delete</i>
                                    <?php _e('remove_image'); ?>
                                </button>
                            </div>
                            
                            <small class="form-text text-muted"><?php _e('image_requirements'); ?></small>
                        </div>
                        
                        <!-- Status -->
                        <div class="form-sidebar-section">
                            <h4 class="form-sidebar-title"><?php _e('status'); ?></h4>
                            
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input" <?php echo $testimonial['is_active'] ? 'checked' : ''; ?>>
                                <label for="is_active" class="form-check-label"><?php _e('active'); ?></label>
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="form-sidebar-section">
                            <h4 class="form-sidebar-title"><?php _e('information'); ?></h4>
                            
                            <div class="info-item">
                                <span class="info-label"><?php _e('created_at'); ?>:</span>
                                <span class="info-value"><?php echo date('d M Y, H:i', strtotime($testimonial['created_at'])); ?></span>
                            </div>
                            
                            <div class="info-item">
                                <span class="info-label"><?php _e('updated_at'); ?>:</span>
                                <span class="info-value"><?php echo date('d M Y, H:i', strtotime($testimonial['updated_at'])); ?></span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="form-sidebar-section">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="material-icons">save</i>
                                <?php _e('save_changes'); ?>
                            </button>
                            
                            <a href="<?php echo $adminUrl; ?>/testimonials" class="btn btn-light btn-block mt-2">
                                <i class="material-icons">cancel</i>
                                <?php _e('cancel'); ?>
                            </a>
                            
                            <a href="<?php echo $adminUrl; ?>/testimonials/delete/<?php echo $testimonial['id']; ?>" class="btn btn-danger btn-block mt-2 delete-btn" data-name="<?php echo htmlspecialchars($testimonial['name']); ?>">
                                <i class="material-icons">delete</i>
                                <?php _e('delete_testimonial'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.form-section {
    margin-bottom: var(--spacing-lg);
}

.form-section-title {
    margin-bottom: var(--spacing-md);
    padding-bottom: var(--spacing-sm);
    border-bottom: 1px solid var(--gray-200);
}

.form-sidebar {
    background-color: var(--gray-100);
    border-radius: var(--border-radius-md);
    padding: var(--spacing-md);
}

.form-sidebar-section {
    margin-bottom: var(--spacing-lg);
}

.form-sidebar-section:last-child {
    margin-bottom: 0;
}

.form-sidebar-title {
    margin-bottom: var(--spacing-md);
    font-size: var(--font-size-md);
}

.image-preview-container {
    margin-bottom: var(--spacing-md);
}

.image-preview {
    width: 100%;
    height: 150px;
    border-radius: var(--border-radius-md);
    background-color: var(--gray-200);
    margin-bottom: var(--spacing-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--gray-600);
    overflow: hidden;
}

.image-preview i {
    font-size: 48px;
}

.image-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rating-input {
    display: flex;
    flex-direction: row-reverse;
    justify-content: flex-end;
}

.rating-input input {
    display: none;
}

.rating-input label {
    cursor: pointer;
    font-size: 0;
    margin: 0;
    padding: 0;
}

.rating-input label i {
    font-size: 24px;
    color: var(--gray-400);
    transition: color var(--transition-fast);
    margin-right: 5px;
}

.rating-input input:checked ~ label i {
    color: var(--warning-color);
}

.rating-input label:hover i,
.rating-input label:hover ~ label i {
    color: var(--warning-color);
}

.info-item {
    margin-bottom: var(--spacing-sm);
    display: flex;
    justify-content: space-between;
}

.info-label {
    color: var(--gray-600);
}

.info-value {
    font-weight: var(--font-weight-medium);
}

.required {
    color: var(--danger-color);
}

/* Fix for CKEditor container */
.ck-editor__editable {
    min-height: 250px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for each language tab
    const editorElements = document.querySelectorAll('.editor');
    const editors = {};
    
    editorElements.forEach(editorElement => {
        if (typeof ClassicEditor !== 'undefined') {
            ClassicEditor
                .create(editorElement)
                .then(editor => {
                    editors[editorElement.id] = editor;
                })
                .catch(error => {
                    console.error('CKEditor initialization error:', error);
                });
        } else {
            console.error('ClassicEditor is not defined. Make sure the CKEditor script is loaded correctly.');
        }
    });
    
    // Initialize Bootstrap tabs
    const tabLinks = document.querySelectorAll('.nav-link');
    tabLinks.forEach(tabLink => {
        tabLink.addEventListener('click', function(event) {
            event.preventDefault();
            
            // Remove active class from all tabs
            tabLinks.forEach(tab => {
                tab.classList.remove('active');
                tab.setAttribute('aria-selected', 'false');
                
                const tabPaneId = tab.getAttribute('href');
                const tabPane = document.querySelector(tabPaneId);
                tabPane.classList.remove('show', 'active');
            });
            
            // Add active class to clicked tab
            this.classList.add('active');
            this.setAttribute('aria-selected', 'true');
            
            const targetId = this.getAttribute('href');
            const targetPane = document.querySelector(targetId);
            targetPane.classList.add('show', 'active');
        });
    });
    
    // Image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image');
    let hasImage = <?php echo $testimonial['image'] ? 'true' : 'false'; ?>;
    
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                imagePreview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                removeImageBtn.style.display = 'inline-block';
                hasImage = true;
            }
            
            reader.readAsDataURL(this.files[0]);
        }
    });
    
    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.innerHTML = '<i class="material-icons">person</i>';
        this.style.display = 'none';
        
        // Add hidden input to remove image
        const removeImageInput = document.createElement('input');
        removeImageInput.type = 'hidden';
        removeImageInput.name = 'remove_image';
        removeImageInput.value = '1';
        document.getElementById('testimonial-form').appendChild(removeImageInput);
        
        hasImage = false;
    });
    
    // Form validation
    const form = document.getElementById('testimonial-form');
    
    form.addEventListener('submit', function(e) {
        let valid = true;
        
        // Check if at least one language has content
        let hasContent = false;
        
        <?php foreach ($languages as $language): ?>
            // Get content from CKEditor instance if exists
            let content<?php echo $language['id']; ?> = '';
            if (editors['editor-<?php echo $language['code']; ?>']) {
                content<?php echo $language['id']; ?> = editors['editor-<?php echo $language['code']; ?>'].getData();
            } else {
                // Fallback to textarea value
                const textarea<?php echo $language['id']; ?> = document.querySelector('[name="details[<?php echo $language['id']; ?>][content]"]');
                if (textarea<?php echo $language['id']; ?>) {
                    content<?php echo $language['id']; ?> = textarea<?php echo $language['id']; ?>.value;
                }
            }
            
            if (content<?php echo $language['id']; ?>.trim() !== '') {
                hasContent = true;
            }
        <?php endforeach; ?>
        
        if (!hasContent) {
            alert("<?php _e('content_required'); ?>");
            valid = false;
        }
        
        if (!valid) {
            e.preventDefault();
        }
    });
    
    // Delete confirmation
    const deleteBtn = document.querySelector('.delete-btn');
    
    if (deleteBtn) {
        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const name = this.dataset.name || 'this testimonial';
            const confirmMessage = "<?php _e('delete_confirm'); ?>".replace('{name}', name);
            
            if (confirm(confirmMessage)) {
                window.location.href = this.getAttribute('href');
            }
        });
    }
});
</script>