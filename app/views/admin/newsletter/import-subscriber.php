<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">file_upload</i> <?php _e('import_subscribers'); ?></h1>
        <p><?php _e('import_subscribers_from_csv'); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-outline-secondary">
            <i class="material-icons">arrow_back</i>
            <?php _e('back_to_subscribers'); ?>
        </a>
        <a href="#" class="btn btn-outline-info" onclick="downloadSampleCSV()">
            <i class="material-icons">file_download</i>
            <?php _e('download_sample_csv'); ?>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><?php _e('upload_csv_file'); ?></h4>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo $adminUrl; ?>/newsletter/import-subscribers" enctype="multipart/form-data" id="import-form">
                    <div class="mb-4">
                        <label for="import_file" class="form-label"><?php _e('csv_file'); ?> <span class="required">*</span></label>
                        <div class="file-upload-area" id="file-upload-area">
                            <input type="file" name="import_file" id="import_file" class="form-control" 
                                   accept=".csv,.txt" required style="display: none;">
                            <div class="upload-placeholder" id="upload-placeholder">
                                <i class="material-icons">cloud_upload</i>
                                <h5><?php _e('drag_drop_csv_file'); ?></h5>
                                <p><?php _e('or_click_to_browse'); ?></p>
                                <button type="button" class="btn btn-outline-primary" onclick="document.getElementById('import_file').click()">
                                    <?php _e('choose_file'); ?>
                                </button>
                            </div>
                            <div class="file-info" id="file-info" style="display: none;">
                                <div class="file-preview">
                                    <i class="material-icons">description</i>
                                    <div class="file-details">
                                        <div class="file-name" id="file-name"></div>
                                        <div class="file-size" id="file-size"></div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile()">
                                        <i class="material-icons">close</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <small class="form-text text-muted"><?php _e('csv_file_requirements'); ?></small>
                    </div>
                    
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="update_existing" id="update_existing" value="1">
                            <label class="form-check-label" for="update_existing">
                                <?php _e('update_existing_subscribers'); ?>
                            </label>
                        </div>
                        <small class="form-text text-muted"><?php _e('update_existing_description'); ?></small>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="import-btn">
                            <i class="material-icons">file_upload</i>
                            <?php _e('import_subscribers'); ?>
                        </button>
                        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-secondary">
                            <?php _e('cancel'); ?>
                        </a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Import Preview -->
        <div class="card mt-4" id="preview-card" style="display: none;">
            <div class="card-header">
                <h4><?php _e('import_preview'); ?></h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm" id="preview-table">
                        <thead>
                            <tr>
                                <th><?php _e('row'); ?></th>
                                <th><?php _e('email'); ?></th>
                                <th><?php _e('name'); ?></th>
                                <th><?php _e('status'); ?></th>
                            </tr>
                        </thead>
                        <tbody id="preview-tbody">
                            <!-- Preview rows will be inserted here -->
                        </tbody>
                    </table>
                </div>
                <div class="preview-summary" id="preview-summary">
                    <!-- Summary will be inserted here -->
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <!-- Instructions -->
        <div class="card">
            <div class="card-header">
                <h5><i class="material-icons">help</i> <?php _e('instructions'); ?></h5>
            </div>
            <div class="card-body">
                <h6><?php _e('csv_format_requirements'); ?></h6>
                <ul class="small">
                    <li><?php _e('csv_req_1'); ?></li>
                    <li><?php _e('csv_req_2'); ?></li>
                    <li><?php _e('csv_req_3'); ?></li>
                    <li><?php _e('csv_req_4'); ?></li>
                </ul>
                
                <h6 class="mt-3"><?php _e('csv_column_headers'); ?></h6>
                <div class="code-block">
                    <code>email,name</code>
                </div>
                
                <h6 class="mt-3"><?php _e('example_rows'); ?></h6>
                <div class="code-block">
                    <code>
                        john@example.com,John Doe<br>
                        jane@example.com,Jane Smith<br>
                        info@company.com,Company Info
                    </code>
                </div>
                
                <div class="alert alert-warning mt-3">
                    <small>
                        <strong><?php _e('important'); ?>:</strong>
                        <?php _e('csv_import_warning'); ?>
                    </small>
                </div>
            </div>
        </div>
        
        <!-- Current Statistics -->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="material-icons">analytics</i> <?php _e('current_statistics'); ?></h5>
            </div>
            <div class="card-body">
                <div class="stat-grid">
                    <div class="stat-item">
                        <span class="stat-number"><?php echo number_format($stats['total'] ?? 0); ?></span>
                        <span class="stat-label"><?php _e('total_subscribers'); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo number_format($stats['active'] ?? 0); ?></span>
                        <span class="stat-label"><?php _e('active_subscribers'); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo number_format($stats['pending'] ?? 0); ?></span>
                        <span class="stat-label"><?php _e('pending_subscribers'); ?></span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number"><?php echo number_format($stats['unsubscribed'] ?? 0); ?></span>
                        <span class="stat-label"><?php _e('unsubscribed'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tips -->
        <div class="card mt-3">
            <div class="card-header">
                <h5><i class="material-icons">lightbulb</i> <?php _e('tips'); ?></h5>
            </div>
            <div class="card-body">
                <ul class="small">
                    <li><?php _e('import_tip_1'); ?></li>
                    <li><?php _e('import_tip_2'); ?></li>
                    <li><?php _e('import_tip_3'); ?></li>
                    <li><?php _e('import_tip_4'); ?></li>
                    <li><?php _e('import_tip_5'); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.required {
    color: #dc3545;
}

.file-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.file-upload-area.drag-over {
    border-color: #4361ee;
    background-color: #f8f9ff;
}

.upload-placeholder {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.upload-placeholder i {
    font-size: 48px;
    margin-bottom: 15px;
    color: #adb5bd;
}

.upload-placeholder h5 {
    margin-bottom: 10px;
    color: #495057;
}

.file-preview {
    display: flex;
    align-items: center;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 6px;
}

.file-preview i {
    font-size: 32px;
    color: #4361ee;
    margin-right: 15px;
}

.file-details {
    flex: 1;
}

.file-name {
    font-weight: 600;
    color: #333;
}

.file-size {
    color: #6c757d;
    font-size: 14px;
}

.code-block {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    padding: 10px;
    font-family: monospace;
    font-size: 12px;
}

.stat-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.stat-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.stat-number {
    display: block;
    font-size: 20px;
    font-weight: 600;
    color: #4361ee;
}

.stat-label {
    display: block;
    font-size: 11px;
    color: #666;
    margin-top: 5px;
}

.preview-summary {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.preview-summary .summary-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 5px;
}

.preview-summary .summary-item:last-child {
    margin-bottom: 0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('import_file');
    const uploadArea = document.getElementById('file-upload-area');
    const placeholder = document.getElementById('upload-placeholder');
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    // File input change
    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            displayFile(e.target.files[0]);
            previewCSV(e.target.files[0]);
        }
    });
    
    // Drag and drop
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('drag-over');
    });
    
    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
    });
    
    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('drag-over');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            displayFile(files[0]);
            previewCSV(files[0]);
        }
    });
    
    function displayFile(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        placeholder.style.display = 'none';
        fileInfo.style.display = 'block';
    }
    
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }
    
    function previewCSV(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const csv = e.target.result;
            const lines = csv.split('\n');
            const headers = lines[0].split(',');
            
            const previewCard = document.getElementById('preview-card');
            const tbody = document.getElementById('preview-tbody');
            const summary = document.getElementById('preview-summary');
            
            // Clear previous preview
            tbody.innerHTML = '';
            
            let validEmails = 0;
            let invalidEmails = 0;
            
            // Preview first 10 rows
            for (let i = 1; i < Math.min(lines.length, 11); i++) {
                if (lines[i].trim() === '') continue;
                
                const cols = lines[i].split(',');
                const email = cols[0] ? cols[0].trim() : '';
                const name = cols[1] ? cols[1].trim() : '';
                
                const isValidEmail = validateEmail(email);
                if (isValidEmail) {
                    validEmails++;
                } else {
                    invalidEmails++;
                }
                
                const row = document.createElement('tr');
                row.className = isValidEmail ? '' : 'table-warning';
                row.innerHTML = `
                    <td>${i}</td>
                    <td>${email} ${!isValidEmail ? '<span class="badge badge-warning">Invalid</span>' : ''}</td>
                    <td>${name}</td>
                    <td><span class="badge badge-info">New</span></td>
                `;
                tbody.appendChild(row);
            }
            
            // Update summary
            summary.innerHTML = `
                <h6><?php _e('import_summary'); ?></h6>
                <div class="summary-item">
                    <span><?php _e('total_rows'); ?>:</span>
                    <span><strong>${lines.length - 1}</strong></span>
                </div>
                <div class="summary-item">
                    <span><?php _e('valid_emails'); ?>:</span>
                    <span class="text-success"><strong>${validEmails}</strong></span>
                </div>
                <div class="summary-item">
                    <span><?php _e('invalid_emails'); ?>:</span>
                    <span class="text-warning"><strong>${invalidEmails}</strong></span>
                </div>
                ${lines.length > 11 ? `<div class="summary-item"><span><?php _e('showing_first_10'); ?></span></div>` : ''}
            `;
            
            previewCard.style.display = 'block';
        };
        reader.readAsText(file);
    }
    
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});

function removeFile() {
    const fileInput = document.getElementById('import_file');
    const placeholder = document.getElementById('upload-placeholder');
    const fileInfo = document.getElementById('file-info');
    const previewCard = document.getElementById('preview-card');
    
    fileInput.value = '';
    placeholder.style.display = 'block';
    fileInfo.style.display = 'none';
    previewCard.style.display = 'none';
}

function downloadSampleCSV() {
    const csvContent = "email,name\njohn@example.com,John Doe\njane@example.com,Jane Smith\ninfo@company.com,Company Info";
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'newsletter_subscribers_sample.csv';
    link.click();
    window.URL.revokeObjectURL(url);
}

// Form validation
document.getElementById('import-form').addEventListener('submit', function(e) {
    const fileInput = document.getElementById('import_file');
    
    if (!fileInput.files.length) {
        e.preventDefault();
        alert('<?php _e("please_select_csv_file"); ?>');
        return;
    }
    
    const file = fileInput.files[0];
    if (!file.name.toLowerCase().endsWith('.csv')) {
        e.preventDefault();
        alert('<?php _e("please_select_csv_file"); ?>');
        return;
    }
    
    // Show loading state
    const importBtn = document.getElementById('import-btn');
    importBtn.disabled = true;
    importBtn.innerHTML = '<i class="material-icons">hourglass_empty</i> <?php _e("importing"); ?>...';
});
</script>