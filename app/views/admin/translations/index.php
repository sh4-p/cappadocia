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
            <form method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="<?php _e('search_keys'); ?>" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-outline-secondary">
                        <i class="material-icons">search</i>
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?php echo $adminUrl; ?>/translations" class="btn btn-outline-secondary">
                            <i class="material-icons">clear</i>
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
    <div class="card-body">
        <div id="translations-container">
            <!-- Dynamic content will be loaded here -->
        </div>
        
        <!-- Loading indicator -->
        <div id="loading-indicator" class="loading-indicator" style="display: none;">
            <div class="spinner"></div>
            <span><?php _e('loading'); ?>...</span>
        </div>
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

/* Inline editing styles */
.editable-cell {
    position: relative;
    cursor: pointer;
    transition: background-color var(--transition-fast);
}

.editable-cell:hover {
    background-color: var(--gray-100);
}

.editable-cell.editing {
    background-color: var(--primary-color-light);
}

.inline-editor {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    padding: 8px;
    border: 2px solid var(--primary-color);
    border-radius: var(--border-radius-sm);
    resize: none;
    font-family: inherit;
    font-size: inherit;
    line-height: inherit;
    z-index: 10;
    min-height: 40px;
}

.inline-editor:focus {
    outline: none;
    border-color: var(--primary-color);
}

.edit-actions {
    position: absolute;
    top: 100%;
    left: 0;
    display: flex;
    gap: var(--spacing-xs);
    padding: var(--spacing-xs);
    background-color: var(--white-color);
    border: 1px solid var(--gray-300);
    border-radius: var(--border-radius-sm);
    box-shadow: var(--shadow-sm);
    z-index: 11;
}

.edit-actions .btn {
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: var(--font-size-sm);
}

/* Loading indicator */
.loading-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: var(--spacing-md);
    padding: var(--spacing-xl);
    color: var(--gray-600);
}

.spinner {
    width: 24px;
    height: 24px;
    border: 3px solid var(--gray-300);
    border-top: 3px solid var(--primary-color);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Saving indicator */
.saving-indicator {
    position: absolute;
    top: 50%;
    right: 8px;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: var(--font-size-sm);
}

.save-success {
    color: var(--success-color);
}

.save-error {
    color: var(--danger-color);
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
class TranslationsManager {
    constructor() {
        this.currentPage = 1;
        this.currentSearch = '';
        this.isLoading = false;
        this.editingCell = null;
        
        this.init();
    }
    
    init() {
        // Load initial data
        this.loadTranslations();
        
        // Setup search functionality
        this.setupSearch();
        
        // Setup modal functionality
        this.setupModals();
    }
    
    async loadTranslations(page = 1, search = '') {
        if (this.isLoading) return;
        
        this.isLoading = true;
        this.currentPage = page;
        this.currentSearch = search;
        
        const loadingIndicator = document.getElementById('loading-indicator');
        const container = document.getElementById('translations-container');
        
        loadingIndicator.style.display = 'flex';
        
        try {
            const params = new URLSearchParams({
                page: page,
                search: search
            });
            
            const response = await fetch(`<?php echo $adminUrl; ?>/translations/ajax-load?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data.success) {
                console.log('Languages data:', data.languages); // Debug
                this.renderTranslations(data.data, data.pagination, data.languages);
            } else {
                throw new Error(data.error || 'Unknown error');
            }
            
        } catch (error) {
            console.error('Error loading translations:', error);
            container.innerHTML = `<div class="alert alert-danger">Error loading translations: ${error.message}</div>`;
        } finally {
            loadingIndicator.style.display = 'none';
            this.isLoading = false;
        }
    }
    
    renderTranslations(translations, pagination, languages) {
        const container = document.getElementById('translations-container');
        
        if (Object.keys(translations).length === 0) {
            container.innerHTML = `
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
            `;
            return;
        }
        
        let html = `
            <div class="translations-table-wrapper">
                <table class="table table-hover translations-table">
                    <thead>
                        <tr>
                            <th class="key-column"><?php _e('key'); ?></th>
        `;
        
        // Language headers
        languages.forEach(lang => {
            html += `
                <th>
                    <div class="language-header">
                        <img src="<?php echo $uploadsUrl; ?>/flags/${lang.flag}" alt="${lang.name}">
                        <span>${lang.name}</span>
                    </div>
                </th>
            `;
        });
        
        html += `
                            <th class="actions-column"><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
        `;
        
        // Translation rows
        Object.entries(translations).forEach(([keyId, translation]) => {
            html += `
                <tr data-key="${this.escapeHtml(translation.key_name)}">
                    <td class="key-column">
                        <div class="key-name" title="${this.escapeHtml(translation.key_name)}">
                            ${this.escapeHtml(translation.key_name)}
                        </div>
                    </td>
            `;
            
            // Translation values for each language
            languages.forEach(lang => {
                const value = translation.translations[lang.id] || '';
                const hasValue = value && value.trim() !== '';
                
                html += `
                    <td class="editable-cell" data-key-id="${keyId}" data-language-id="${lang.id}">
                        <div class="translation-value">
                            ${hasValue ? 
                                `<div class="ellipsis" title="${this.escapeHtml(value)}">${this.escapeHtml(value)}</div>` :
                                '<em class="no-translation"><?php _e("no_translation"); ?></em>'
                            }
                        </div>
                    </td>
                `;
            });
            
            // Actions
            html += `
                    <td class="actions-column">
                        <div class="actions">
            `;
            
            languages.forEach(lang => {
                html += `
                    <a href="<?php echo $adminUrl; ?>/translations/edit/${lang.code}?key=${keyId}" class="action-btn" title="Edit in ${lang.name}">
                        <img src="<?php echo $uploadsUrl; ?>/flags/${lang.flag}" alt="${lang.name}" class="mini-flag">
                    </a>
                `;
            });
            
            html += `
                            <a href="<?php echo $adminUrl; ?>/translations/delete-key/${keyId}" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_key_confirm'); ?>">
                                <i class="material-icons">delete</i>
                            </a>
                        </div>
                    </td>
                </tr>
            `;
        });
        
        html += `
                    </tbody>
                </table>
            </div>
        `;
        
        // Pagination
        if (pagination.total_pages > 1) {
            html += this.renderPagination(pagination);
        }
        
        container.innerHTML = html;
        
        // Setup event listeners
        this.setupTableEvents();
    }
    
    renderPagination(pagination) {
        const startItem = (pagination.current_page - 1) * pagination.per_page + 1;
        const endItem = Math.min(pagination.current_page * pagination.per_page, pagination.total_items);
        
        let html = `
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing ${startItem} to ${endItem} of ${pagination.total_items} results
                </div>
                <nav class="pagination">
        `;
        
        // Previous button
        if (pagination.has_prev) {
            html += `
                <a href="#" class="pagination-link" data-page="${pagination.prev_page}">
                    <i class="material-icons">chevron_left</i>
                    <?php _e('previous'); ?>
                </a>
            `;
        }
        
        // Page numbers
        const startPage = Math.max(1, pagination.current_page - 2);
        const endPage = Math.min(pagination.total_pages, pagination.current_page + 2);
        
        if (startPage > 1) {
            html += `<a href="#" class="pagination-link" data-page="1">1</a>`;
            if (startPage > 2) {
                html += `<span class="pagination-ellipsis">...</span>`;
            }
        }
        
        for (let i = startPage; i <= endPage; i++) {
            if (i === pagination.current_page) {
                html += `<span class="pagination-link active">${i}</span>`;
            } else {
                html += `<a href="#" class="pagination-link" data-page="${i}">${i}</a>`;
            }
        }
        
        if (endPage < pagination.total_pages) {
            if (endPage < pagination.total_pages - 1) {
                html += `<span class="pagination-ellipsis">...</span>`;
            }
            html += `<a href="#" class="pagination-link" data-page="${pagination.total_pages}">${pagination.total_pages}</a>`;
        }
        
        // Next button
        if (pagination.has_next) {
            html += `
                <a href="#" class="pagination-link" data-page="${pagination.next_page}">
                    <?php _e('next'); ?>
                    <i class="material-icons">chevron_right</i>
                </a>
            `;
        }
        
        html += `
                </nav>
            </div>
        `;
        
        return html;
    }
    
    setupTableEvents() {
        // Pagination clicks
        document.querySelectorAll('.pagination-link[data-page]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(link.dataset.page);
                this.loadTranslations(page, this.currentSearch);
            });
        });
        
        // Inline editing
        document.querySelectorAll('.editable-cell').forEach(cell => {
            cell.addEventListener('click', (e) => {
                if (this.editingCell) {
                    this.cancelEdit();
                }
                this.startEdit(cell);
            });
        });
        
        // Delete confirmation
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const confirmMessage = btn.dataset.confirm || '<?php _e("delete_confirm"); ?>';
                if (confirm(confirmMessage)) {
                    window.location.href = btn.getAttribute('href');
                }
            });
        });
    }
    
    startEdit(cell) {
        const keyId = cell.dataset.keyId;
        const languageId = cell.dataset.languageId;
        const valueDiv = cell.querySelector('.translation-value');
        const currentValue = valueDiv.textContent.trim();
        const isEmptyTranslation = currentValue === '<?php _e("no_translation"); ?>';
        
        // Create editor
        const editor = document.createElement('textarea');
        editor.className = 'inline-editor';
        editor.value = isEmptyTranslation ? '' : currentValue;
        
        // Create actions
        const actions = document.createElement('div');
        actions.className = 'edit-actions';
        actions.innerHTML = `
            <button type="button" class="btn btn-primary btn-sm save-btn">
                <i class="material-icons">check</i>
            </button>
            <button type="button" class="btn btn-light btn-sm cancel-btn">
                <i class="material-icons">close</i>
            </button>
        `;
        
        // Replace content
        cell.classList.add('editing');
        cell.style.position = 'relative';
        cell.appendChild(editor);
        cell.appendChild(actions);
        
        // Focus and select
        editor.focus();
        editor.select();
        
        this.editingCell = {
            cell: cell,
            editor: editor,
            actions: actions,
            keyId: keyId,
            languageId: languageId,
            originalValue: isEmptyTranslation ? '' : currentValue
        };
        
        // Event listeners
        actions.querySelector('.save-btn').addEventListener('click', () => this.saveEdit());
        actions.querySelector('.cancel-btn').addEventListener('click', () => this.cancelEdit());
        
        editor.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && e.ctrlKey) {
                this.saveEdit();
            } else if (e.key === 'Escape') {
                this.cancelEdit();
            }
        });
        
        // Click outside to cancel
        document.addEventListener('click', this.handleClickOutside.bind(this), true);
    }
    
    handleClickOutside(e) {
        if (this.editingCell && !this.editingCell.cell.contains(e.target)) {
            this.cancelEdit();
        }
    }
    
    async saveEdit() {
        if (!this.editingCell) return;
        
        const { cell, editor, keyId, languageId, originalValue } = this.editingCell;
        const newValue = editor.value.trim();
        
        // Show saving indicator
        const savingIndicator = document.createElement('div');
        savingIndicator.className = 'saving-indicator';
        savingIndicator.innerHTML = '<i class="material-icons">hourglass_empty</i>';
        cell.appendChild(savingIndicator);
        
        try {
            const formData = new FormData();
            formData.append('key_id', keyId);
            formData.append('language_id', languageId);
            formData.append('value', newValue);
            
            const response = await fetch('<?php echo $adminUrl; ?>/translations/ajax-update', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data.success) {
                // Update cell content
                const valueDiv = cell.querySelector('.translation-value');
                if (newValue === '') {
                    valueDiv.innerHTML = '<em class="no-translation"><?php _e("no_translation"); ?></em>';
                } else {
                    valueDiv.innerHTML = `<div class="ellipsis" title="${this.escapeHtml(newValue)}">${this.escapeHtml(newValue)}</div>`;
                }
                
                // Show success indicator
                savingIndicator.className = 'saving-indicator save-success';
                savingIndicator.innerHTML = '<i class="material-icons">check</i>';
                
                setTimeout(() => {
                    this.cancelEdit();
                }, 1000);
            } else {
                throw new Error(data.error || 'Save failed');
            }
            
        } catch (error) {
            console.error('Error saving translation:', error);
            
            // Show error indicator
            savingIndicator.className = 'saving-indicator save-error';
            savingIndicator.innerHTML = '<i class="material-icons">error</i>';
            
            alert('Error saving translation: ' + error.message);
        }
    }
    
    cancelEdit() {
        if (!this.editingCell) return;
        
        const { cell, editor, actions } = this.editingCell;
        
        // Remove editor and actions
        if (editor.parentNode) editor.remove();
        if (actions.parentNode) actions.remove();
        
        // Remove any saving indicators
        const savingIndicator = cell.querySelector('.saving-indicator');
        if (savingIndicator) savingIndicator.remove();
        
        // Reset cell
        cell.classList.remove('editing');
        cell.style.position = '';
        
        // Remove click outside listener
        document.removeEventListener('click', this.handleClickOutside.bind(this), true);
        
        this.editingCell = null;
    }
    
    setupSearch() {
        const searchForm = document.querySelector('.search-form');
        const searchInput = searchForm.querySelector('input[name="search"]');
        
        searchForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const searchValue = searchInput.value.trim();
            this.loadTranslations(1, searchValue);
        });
        
        // Clear search
        const clearBtn = searchForm.querySelector('.btn:has(.material-icons)');
        if (clearBtn && clearBtn.querySelector('.material-icons').textContent === 'clear') {
            clearBtn.addEventListener('click', (e) => {
                e.preventDefault();
                searchInput.value = '';
                this.loadTranslations(1, '');
            });
        }
    }
    
    setupModals() {
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
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    new TranslationsManager();
});
</script>