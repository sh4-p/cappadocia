<?php
/**
 * Admin Translation Edit View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php echo sprintf(__('edit_translations_for'), $language['name']); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/translations" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_translations'); ?></span>
        </a>
        <a href="<?php echo $adminUrl; ?>/translations/export/<?php echo $language['id']; ?>" class="btn btn-light">
            <i class="material-icons">file_download</i>
            <span><?php _e('export'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="language-info">
            <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $language['flag']; ?>" alt="<?php echo $language['name']; ?>">
            <h3 class="language-name"><?php echo $language['name']; ?></h3>
            <span class="language-code"><?php echo $language['code']; ?></span>
        </div>
        <div class="card-actions">
            <input type="text" id="search-input" class="form-control" placeholder="<?php _e('search_keys'); ?>">
        </div>
    </div>
    <div class="card-body">
        <form action="<?php echo $adminUrl; ?>/translations/edit/<?php echo $language['code']; ?>" method="post">
            <?php if (empty($translations)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="material-icons">translate</i>
                    </div>
                    <h3 class="empty-state-title"><?php _e('no_translation_keys_found'); ?></h3>
                    <p class="empty-state-description"><?php _e('no_translation_keys_description'); ?></p>
                    <a href="<?php echo $adminUrl; ?>/translations" class="btn btn-primary">
                        <i class="material-icons">add</i>
                        <?php _e('add_keys'); ?>
                    </a>
                </div>
            <?php else: ?>
                <div class="translations-list">
                    <?php foreach ($translations as $key): ?>
                        <div class="translation-item" data-key="<?php echo htmlspecialchars($key['key_name']); ?>">
                            <div class="translation-key">
                                <span class="key-name"><?php echo htmlspecialchars($key['key_name']); ?></span>
                            </div>
                            <div class="translation-value">
                                <textarea name="translations[<?php echo $key['id']; ?>]" class="form-control" rows="3"><?php echo htmlspecialchars($key['value'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons">save</i>
                        <?php _e('save_translations'); ?>
                    </button>
                    <a href="<?php echo $adminUrl; ?>/translations" class="btn btn-light">
                        <i class="material-icons">cancel</i>
                        <?php _e('cancel'); ?>
                    </a>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<style>
.language-info {
    display: flex;
    align-items: center;
    gap: var(--spacing-md);
}

.language-info img {
    width: 30px;
    height: 20px;
    object-fit: cover;
    border-radius: var(--border-radius-sm);
}

.language-name {
    font-size: var(--font-size-lg);
    margin: 0;
}

.language-code {
    font-family: monospace;
    background-color: var(--gray-100);
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-sm);
}

.translations-list {
    display: grid;
    gap: var(--spacing-md);
}

.translation-item {
    display: grid;
    gap: var(--spacing-xs);
    padding: var(--spacing-md);
    background-color: var(--gray-100);
    border-radius: var(--border-radius-md);
}

.translation-key {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.key-name {
    font-family: monospace;
    font-weight: var(--font-weight-medium);
    font-size: var(--font-size-sm);
}

.translation-value {
    width: 100%;
}

.form-actions {
    margin-top: var(--spacing-xl);
    display: flex;
    gap: var(--spacing-md);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const translationItems = document.querySelectorAll('.translation-item');
    
    if (searchInput && translationItems.length > 0) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            translationItems.forEach(item => {
                const key = item.dataset.key.toLowerCase();
                
                if (key.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Auto-resize textareas
    const textareas = document.querySelectorAll('textarea');
    
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Trigger resize on load
        textarea.dispatchEvent(new Event('input'));
    });
    
    // Highlight focused item
    translationItems.forEach(item => {
        const textarea = item.querySelector('textarea');
        
        textarea.addEventListener('focus', function() {
            item.classList.add('focused');
        });
        
        textarea.addEventListener('blur', function() {
            item.classList.remove('focused');
        });
    });
    
    // Scroll to focused key
    const keyParam = new URLSearchParams(window.location.search).get('key');
    
    if (keyParam) {
        const targetItem = document.querySelector(`.translation-item[data-key="${keyParam}"]`);
        
        if (targetItem) {
            targetItem.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            
            setTimeout(() => {
                const textarea = targetItem.querySelector('textarea');
                textarea.focus();
            }, 500);
        }
    }
});
</script>