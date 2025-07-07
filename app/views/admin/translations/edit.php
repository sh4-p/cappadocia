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
            <form method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="<?php _e('search_keys'); ?>" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="material-icons">search</i>
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?php echo $adminUrl; ?>/translations/edit/<?php echo $language['code']; ?>" class="btn btn-outline-secondary">
                            <i class="material-icons">clear</i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <form action="<?php echo $adminUrl; ?>/translations/edit/<?php echo $language['code']; ?>" method="post">
            <?php if (isset($pagination['current_page']) && $pagination['current_page'] > 1): ?>
                <input type="hidden" name="page" value="<?php echo $pagination['current_page']; ?>">
            <?php endif; ?>
            <?php if (!empty($search)): ?>
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($search); ?>">
            <?php endif; ?>
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
                    <?php foreach ($translations as $keyId => $key): ?>
                        <div class="translation-item" data-key="<?php echo htmlspecialchars($key['key_name'] ?? ''); ?>">
                            <div class="translation-key">
                                <span class="key-name"><?php echo htmlspecialchars($key['key_name'] ?? ''); ?></span>
                            </div>
                            <div class="translation-value">
                                <textarea name="translations[<?php echo $keyId; ?>]" class="form-control" rows="3"><?php echo htmlspecialchars($key['value'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            <?php 
                            $startItem = ($pagination['current_page'] - 1) * $pagination['per_page'] + 1;
                            $endItem = min($pagination['current_page'] * $pagination['per_page'], $pagination['total_items']);
                            echo sprintf(__('showing_results'), $startItem, $endItem, $pagination['total_items']); 
                            ?>
                        </div>
                        <nav class="pagination">
                            <?php if ($pagination['has_prev']): ?>
                                <a href="?page=<?php echo $pagination['prev_page']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link">
                                    <i class="material-icons">chevron_left</i>
                                    <?php _e('previous'); ?>
                                </a>
                            <?php endif; ?>

                            <?php
                            $startPage = max(1, $pagination['current_page'] - 2);
                            $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            if ($startPage > 1): ?>
                                <a href="?page=1<?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link">1</a>
                                <?php if ($startPage > 2): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <?php if ($i == $pagination['current_page']): ?>
                                    <span class="pagination-link active"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?page=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>

                            <?php if ($endPage < $pagination['total_pages']): ?>
                                <?php if ($endPage < $pagination['total_pages'] - 1): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                                <a href="?page=<?php echo $pagination['total_pages']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link"><?php echo $pagination['total_pages']; ?></a>
                            <?php endif; ?>

                            <?php if ($pagination['has_next']): ?>
                                <a href="?page=<?php echo $pagination['next_page']; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>" class="pagination-link">
                                    <?php _e('next'); ?>
                                    <i class="material-icons">chevron_right</i>
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                <?php endif; ?>
                
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

/* Pagination styles */
.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: var(--spacing-lg);
    padding-top: var(--spacing-lg);
    border-top: 1px solid var(--gray-200);
}

.pagination-info {
    color: var(--gray-600);
    font-size: var(--font-size-sm);
}

.pagination {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
}

.pagination-link {
    display: flex;
    align-items: center;
    gap: var(--spacing-xs);
    padding: var(--spacing-sm) var(--spacing-md);
    border-radius: var(--border-radius-md);
    background-color: var(--white-color);
    border: 1px solid var(--gray-300);
    color: var(--dark-color);
    text-decoration: none;
    transition: all var(--transition-fast);
    font-size: var(--font-size-sm);
}

.pagination-link:hover {
    background-color: var(--gray-100);
    border-color: var(--gray-400);
}

.pagination-link.active {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
    color: var(--white-color);
}

.pagination-ellipsis {
    padding: var(--spacing-sm) var(--spacing-xs);
    color: var(--gray-500);
}

.search-form {
    display: flex;
    gap: var(--spacing-sm);
}

.input-group {
    display: flex;
    align-items: stretch;
}

.input-group .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
}

.input-group .btn {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
}

@media (max-width: 768px) {
    .pagination-wrapper {
        flex-direction: column;
        gap: var(--spacing-md);
        align-items: flex-start;
    }
    
    .pagination {
        overflow-x: auto;
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
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