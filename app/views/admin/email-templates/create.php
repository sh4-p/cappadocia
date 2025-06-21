<!-- Email Templates Create View - app/views/admin/email-templates/create.php -->

<div class="admin-content-header">
    <div class="content-header-left">
        <h1 class="content-header-title">
            <i class="material-icons">add</i>
            <?php _e('add_email_template'); ?>
        </h1>
        <p class="content-header-subtitle"><?php _e('create_new_email_template'); ?></p>
    </div>
    <div class="content-header-right">
        <a href="<?php echo $adminUrl; ?>/email-templates" class="btn btn-secondary">
            <i class="material-icons">arrow_back</i>
            <?php _e('back_to_templates'); ?>
        </a>
    </div>
</div>

<div class="admin-content-body">
    <form method="POST" class="email-template-form">
        <div class="row">
            <!-- Template Form -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">settings</i>
                            <?php _e('template_details'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="template_key" class="form-label required">
                                <?php _e('template_key'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <select name="template_key" id="template_key" class="form-control" required>
                                <option value=""><?php _e('select_template_key'); ?></option>
                                <?php foreach ($availableKeys as $key => $info): ?>
                                    <option value="<?php echo htmlspecialchars($key); ?>" 
                                            <?php echo (isset($templateKey) && $templateKey === $key) ? 'selected' : ''; ?>
                                            data-description="<?php echo htmlspecialchars($info['description']); ?>"
                                            data-variables="<?php echo htmlspecialchars(json_encode($info['variables'])); ?>">
                                        <?php echo htmlspecialchars($key); ?> - <?php echo htmlspecialchars($info['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                                <option value="custom" <?php echo (isset($templateKey) && $templateKey === 'custom') ? 'selected' : ''; ?>>
                                    <?php _e('custom_template_key'); ?>
                                </option>
                            </select>
                            <small class="form-help"><?php _e('template_key_help'); ?></small>
                        </div>

                        <div class="form-group" id="custom-key-group" style="display: none;">
                            <label for="custom_template_key" class="form-label">
                                <?php _e('custom_template_key'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="custom_template_key" id="custom_template_key" 
                                   class="form-control" placeholder="<?php _e('enter_custom_key'); ?>">
                            <small class="form-help"><?php _e('custom_key_help'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="name" class="form-label required">
                                <?php _e('template_name'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" id="name" class="form-control" 
                                   value="<?php echo htmlspecialchars($name ?? ''); ?>" 
                                   placeholder="<?php _e('enter_template_name'); ?>" required>
                            <small class="form-help"><?php _e('template_name_help'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">
                                <?php _e('description'); ?>
                            </label>
                            <textarea name="description" id="description" class="form-control" rows="2" 
                                      placeholder="<?php _e('enter_description'); ?>"><?php echo htmlspecialchars($description ?? ''); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="form-label required">
                                <?php _e('email_subject'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="subject" id="subject" class="form-control" 
                                   value="<?php echo htmlspecialchars($subject ?? ''); ?>" 
                                   placeholder="<?php _e('enter_email_subject'); ?>" required>
                            <small class="form-help"><?php _e('subject_help'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="body" class="form-label required">
                                <?php _e('email_body'); ?>
                                <span class="text-danger">*</span>
                            </label>
                            <textarea name="body" id="body" class="form-control code-editor" rows="15" 
                                      placeholder="<?php _e('enter_email_body'); ?>" required><?php echo htmlspecialchars($body ?? ''); ?></textarea>
                            <small class="form-help"><?php _e('body_help'); ?></small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" 
                                       <?php echo (isset($isActive) && $isActive) ? 'checked' : 'checked'; ?>>
                                <label for="is_active" class="form-check-label">
                                    <?php _e('template_active'); ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Template Info Sidebar -->
            <div class="col-lg-4">
                <!-- Template Variables -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">code</i>
                            <?php _e('available_variables'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="template-variables">
                            <p class="text-muted"><?php _e('select_template_key_to_see_variables'); ?></p>
                        </div>
                    </div>
                </div>

                <!-- Template Info -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <i class="material-icons">info</i>
                            <?php _e('template_syntax'); ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="syntax-help">
                            <h6><?php _e('variable_syntax'); ?></h6>
                            <p><code>{{variable_name}}</code></p>
                            
                            <h6><?php _e('conditional_blocks'); ?></h6>
                            <p><code>{{#variable}}...{{/variable}}</code></p>
                            
                            <h6><?php _e('examples'); ?>:</h6>
                            <div class="code-examples">
                                <p><code>Hello {{first_name}}!</code></p>
                                <p><code>{{#phone}}Phone: {{phone}}{{/phone}}</code></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body">
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="material-icons">save</i>
                                <?php _e('save_template'); ?>
                            </button>
                            <a href="<?php echo $adminUrl; ?>/email-templates" class="btn btn-secondary btn-block">
                                <i class="material-icons">cancel</i>
                                <?php _e('cancel'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const templateKeySelect = document.getElementById('template_key');
    const customKeyGroup = document.getElementById('custom-key-group');
    const customKeyInput = document.getElementById('custom_template_key');
    const nameInput = document.getElementById('name');
    const descriptionInput = document.getElementById('description');
    const variablesContainer = document.getElementById('template-variables');
    
    templateKeySelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (this.value === 'custom') {
            customKeyGroup.style.display = 'block';
            customKeyInput.required = true;
            updateVariables([]);
        } else if (this.value === '') {
            customKeyGroup.style.display = 'none';
            customKeyInput.required = false;
            updateVariables([]);
        } else {
            customKeyGroup.style.display = 'none';
            customKeyInput.required = false;
            
            // Auto-fill name and description
            if (selectedOption.textContent) {
                const parts = selectedOption.textContent.split(' - ');
                if (parts.length > 1) {
                    nameInput.value = parts[1];
                }
            }
            
            if (selectedOption.dataset.description) {
                descriptionInput.value = selectedOption.dataset.description;
            }
            
            // Update variables
            if (selectedOption.dataset.variables) {
                try {
                    const variables = JSON.parse(selectedOption.dataset.variables);
                    updateVariables(variables);
                } catch (e) {
                    console.error('Error parsing variables:', e);
                    updateVariables([]);
                }
            }
        }
    });
    
    function updateVariables(variables) {
        if (variables.length === 0) {
            variablesContainer.innerHTML = '<p class="text-muted"><?php _e('no_variables_available'); ?></p>';
            return;
        }
        
        let html = '<div class="variables-list">';
        variables.forEach(function(variable) {
            html += '<div class="variable-item">';
            html += '<code class="variable-code">{{' + variable + '}}</code>';
            html += '</div>';
        });
        html += '</div>';
        
        variablesContainer.innerHTML = html;
    }
    
    // Initialize form
    if (templateKeySelect.value) {
        templateKeySelect.dispatchEvent(new Event('change'));
    }
});
</script>

<style>
.email-template-form .form-group {
    margin-bottom: 20px;
}

.form-label.required::after {
    content: '';
}

.code-editor {
    font-family: 'Courier New', monospace;
    font-size: 13px;
}

.syntax-help h6 {
    margin-top: 15px;
    margin-bottom: 5px;
    font-size: 12px;
    text-transform: uppercase;
    color: #666;
    letter-spacing: 0.5px;
}

.syntax-help h6:first-child {
    margin-top: 0;
}

.syntax-help code {
    background: #f8f9fa;
    padding: 2px 6px;
    border-radius: 3px;
    font-size: 12px;
}

.code-examples p {
    margin: 5px 0;
    font-size: 13px;
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
    cursor: pointer;
    transition: background-color 0.2s;
}

.variable-code:hover {
    background: #bbdefb;
}

.form-actions {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.form-help {
    display: block;
    margin-top: 5px;
    font-size: 12px;
    color: #666;
}

#custom-key-group {
    border: 1px dashed #ddd;
    padding: 15px;
    border-radius: 4px;
    background: #fafafa;
}
</style>