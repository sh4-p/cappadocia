<?php
/**
 * Admin Translations List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('translations'); ?></h1>
    <div class="page-actions">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-key-modal">
            <i class="material-icons">add</i>
            <span><?php _e('add_key'); ?></span>
        </button>
        <button type="button" class="btn btn-light" data-toggle="modal" data-target="#import-modal">
            <i class="material-icons">file_upload</i>
            <span><?php _e('import'); ?></span>
        </button>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title"><?php _e('translation_keys'); ?></h3>
        <div class="card-actions">
            <input type="text" id="search-input" class="form-control" placeholder="<?php _e('search_keys'); ?>">
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($translations)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">translate</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_translation_keys_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_translation_keys_description'); ?></p>
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-key-modal">
                    <i class="material-icons">add</i>
                    <?php _e('add_first_key'); ?>
                </button>
            </div>
        <?php else: ?>
            <div class="translations-table-wrapper">
                <table class="table table-hover translations-table">
                    <thead>
                        <tr>
                            <th class="key-column"><?php _e('key'); ?></th>
                            <?php foreach ($languages as $lang): ?>
                                <th>
                                    <div class="language-header">
                                        <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['flag']; ?>" alt="<?php echo $lang['name']; ?>">
                                        <span><?php echo $lang['name']; ?></span>
                                    </div>
                                </th>
                            <?php endforeach; ?>
                            <th class="actions-column"><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($translations as $keyId => $translation): ?>
                            <tr data-key="<?php echo htmlspecialchars($translation['key_name']); ?>">
                                <td class="key-column">
                                    <div class="key-name" title="<?php echo htmlspecialchars($translation['key_name']); ?>">
                                        <?php echo htmlspecialchars($translation['key_name']); ?>
                                    </div>
                                </td>
                                <?php foreach ($languages as $lang): ?>
                                    <td>
                                        <div class="translation-value">
                                            <?php if (isset($translation['translations'][$lang['id']])): ?>
                                                <div class="ellipsis" title="<?php echo htmlspecialchars($translation['translations'][$lang['id']]); ?>">
                                                    <?php echo htmlspecialchars($translation['translations'][$lang['id']]); ?>
                                                </div>
                                            <?php else: ?>
                                                <em class="no-translation"><?php _e('no_translation'); ?></em>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endforeach; ?>
                                <td class="actions-column">
                                    <div class="actions">
                                        <?php foreach ($languages as $lang): ?>
                                            <a href="<?php echo $adminUrl; ?>/translations/edit/<?php echo $lang['code']; ?>?key=<?php echo $keyId; ?>" class="action-btn" title="<?php echo sprintf(__('edit_in'), $lang['name']); ?>">
                                                <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['flag']; ?>" alt="<?php echo $lang['name']; ?>" class="mini-flag">
                                            </a>
                                        <?php endforeach; ?>
                                        <a href="<?php echo $adminUrl; ?>/translations/delete-key/<?php echo $keyId; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_key_confirm'); ?>">
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

<!-- Add Key Modal -->
<div class="modal" id="add-key-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo $adminUrl; ?>/translations/add-key" method="post">
                <div class="modal-header">
                    <h3 class="modal-title"><?php _e('add_translation_key'); ?></h3>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="key_name" class="form-label"><?php _e('key_name'); ?> <span class="required">*</span></label>
                        <input type="text" id="key_name" name="key_name" class="form-control" required>
                        <small class="form-text"><?php _e('key_name_help'); ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        <?php _e('cancel'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons">add</i>
                        <?php _e('add_key'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Import Modal -->
<div class="modal" id="import-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?php echo $adminUrl; ?>/translations/import" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h3 class="modal-title"><?php _e('import_translations'); ?></h3>
                    <button type="button" class="close" data-dismiss="modal">
                        <i class="material-icons">close</i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="language_id" class="form-label"><?php _e('language'); ?> <span class="required">*</span></label>
                        <select id="language_id" name="language_id" class="form-select" required>
                            <option value=""><?php _e('select_language'); ?></option>
                            <?php foreach ($languages as $lang): ?>
                                <option value="<?php echo $lang['id']; ?>"><?php echo $lang['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="import_file" class="form-label"><?php _e('file'); ?> <span class="required">*</span></label>
                        <input type="file" id="import_file" name="import_file" class="form-control" accept=".json" required>
                        <small class="form-text"><?php _e('import_file_help'); ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">
                        <?php _e('cancel'); ?>
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="material-icons">file_upload</i>
                        <?php _e('import'); ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="export-links">
    <h3><?php _e('export_translations'); ?></h3>
    <div class="export-grid">
        <?php foreach ($languages as $lang): ?>
            <a href="<?php echo $adminUrl; ?>/translations/export/<?php echo $lang['id']; ?>" class="export-link">
                <img src="<?php echo $uploadsUrl; ?>/flags/<?php echo $lang['flag']; ?>" alt="<?php echo $lang['name']; ?>">
                <span><?php echo $lang['name']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<style>
.language-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.language-header img {
    width: 20px;
    height: 15px;
    object-fit: cover;
    border-radius: 2px;
}

.translations-table-wrapper {
    overflow-x: auto;
}

.translations-table {
    width: 100%;
    table-layout: fixed;
}

.translations-table th,
.translations-table td {
    vertical-align: middle;
}

.key-column {
    width: 250px;
}

.actions-column {
    width: 120px;
}

.key-name {
    font-family: monospace;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding: 0.25rem 0.5rem;
    background-color: var(--gray-100);
    border-radius: var(--border-radius-sm);
}

.translation-value {
    max-width: 100%;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.ellipsis {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.no-translation {
    opacity: 0.5;
    font-style: italic;
}

.mini-flag {
    width: 16px;
    height: 12px;
    object-fit: cover;
    border-radius: 2px;
}

.export-links {
    margin-top: var(--spacing-xl);
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    padding: var(--spacing-lg);
    box-shadow: var(--shadow-sm);
}

.export-links h3 {
    margin-bottom: var(--spacing-md);
}

.export-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: var(--spacing-md);
}

.export-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: var(--spacing-sm);
    border-radius: var(--border-radius-md);
    background-color: var(--gray-100);
    transition: background-color var(--transition-fast);
}

.export-link:hover {
    background-color: var(--gray-200);
}

.export-link img {
    width: 24px;
    height: 18px;
    object-fit: cover;
    border-radius: 3px;
}

/* Modal styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: var(--z-index-modal);
    opacity: 0;
    visibility: hidden;
    transition: opacity var(--transition-medium), visibility var(--transition-medium);
}

.modal.show {
    opacity: 1;
    visibility: visible;
}

.modal-dialog {
    width: 100%;
    max-width: 500px;
    margin: 30px auto;
}

.modal-content {
    background-color: var(--white-color);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-lg);
    overflow: hidden;
    transform: translateY(-20px);
    transition: transform var(--transition-medium);
}

.modal.show .modal-content {
    transform: translateY(0);
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: var(--spacing-md) var(--spacing-lg);
    border-bottom: 1px solid var(--gray-200);
}

.modal-title {
    margin: 0;
    font-size: var(--font-size-lg);
}

.modal-body {
    padding: var(--spacing-lg);
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    gap: var(--spacing-md);
    padding: var(--spacing-md) var(--spacing-lg);
    border-top: 1px solid var(--gray-200);
}

.close {
    width: 36px;
    height: 36px;
    border-radius: var(--border-radius-circle);
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--gray-200);
    color: var(--gray-700);
    transition: background-color var(--transition-fast), color var(--transition-fast);
}

.close:hover {
    background-color: var(--gray-300);
    color: var(--dark-color);
}

body.modal-open {
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('search-input');
    const tableRows = document.querySelectorAll('.translations-table tbody tr');
    
    if (searchInput && tableRows.length > 0) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            tableRows.forEach(row => {
                const key = row.dataset.key.toLowerCase();
                
                if (key.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
    
    // Delete confirmation
    const deleteBtns = document.querySelectorAll('.delete-btn');
    
    deleteBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const confirmMessage = this.dataset.confirm || '<?php _e("delete_confirm"); ?>';
            
            if (confirm(confirmMessage)) {
                window.location.href = this.getAttribute('href');
            }
        });
    });
    
    // Modal functionality
    const modals = document.querySelectorAll('.modal');
    const modalToggles = document.querySelectorAll('[data-toggle="modal"]');
    const modalCloses = document.querySelectorAll('[data-dismiss="modal"]');
    
    modalToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const modal = document.querySelector(targetId);
            
            if (modal) {
                modal.classList.add('show');
                document.body.classList.add('modal-open');
            }
        });
    });
    
    modalCloses.forEach(close => {
        close.addEventListener('click', function() {
            const modal = this.closest('.modal');
            
            if (modal) {
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
            }
        });
    });
    
    window.addEventListener('click', function(event) {
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
            }
        });
    });
});
</script>