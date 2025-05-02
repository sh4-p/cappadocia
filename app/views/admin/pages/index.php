<?php
/**
 * Admin Pages List View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('pages'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/pages/create" class="btn btn-primary">
            <i class="material-icons">add</i>
            <span><?php _e('add_page'); ?></span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($pages)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">description</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_pages_found'); ?></h3>
                <p class="empty-state-description"><?php _e('no_pages_description'); ?></p>
                <a href="<?php echo $adminUrl; ?>/pages/create" class="btn btn-primary">
                    <i class="material-icons">add</i>
                    <?php _e('add_first_page'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover datatable">
                    <thead>
                        <tr>
                            <th><?php _e('id'); ?></th>
                            <th><?php _e('title'); ?></th>
                            <th><?php _e('slug'); ?></th>
                            <th><?php _e('template'); ?></th>
                            <th><?php _e('order'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('updated_at'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody class="sortable" data-action="<?php echo $adminUrl; ?>/pages/update-order">
                        <?php foreach ($pages as $page): ?>
                            <tr data-id="<?php echo $page['id']; ?>">
                                <td><?php echo $page['id']; ?></td>
                                <td>
                                    <a href="<?php echo $adminUrl; ?>/pages/edit/<?php echo $page['id']; ?>">
                                        <?php echo $page['title']; ?>
                                    </a>
                                </td>
                                <td>
                                    <code><?php echo $page['slug']; ?></code>
                                </td>
                                <td>
                                    <span class="template-badge">
                                        <?php echo $page['template'] ? ucfirst($page['template']) : __('default'); ?>
                                    </span>
                                </td>
                                <td class="order-handle">
                                    <i class="material-icons handle">drag_indicator</i>
                                    <span><?php echo $page['order_number']; ?></span>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo $page['is_active'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $page['is_active'] ? __('active') : __('inactive'); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y, H:i', strtotime($page['updated_at'])); ?></td>
                                <td>
                                    <div class="actions">
                                        <a href="<?php echo $appUrl . '/' . $currentLang; ?>/page/<?php echo $page['slug']; ?>" class="action-btn" title="<?php _e('view'); ?>" target="_blank">
                                            <i class="material-icons">visibility</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/pages/edit/<?php echo $page['id']; ?>" class="action-btn" title="<?php _e('edit'); ?>">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/pages/toggle-status/<?php echo $page['id']; ?>" class="action-btn" title="<?php echo $page['is_active'] ? __('deactivate') : __('activate'); ?>">
                                            <i class="material-icons"><?php echo $page['is_active'] ? 'toggle_on' : 'toggle_off'; ?></i>
                                        </a>
                                        <a href="<?php echo $adminUrl; ?>/pages/delete/<?php echo $page['id']; ?>" class="action-btn delete-btn" title="<?php _e('delete'); ?>" data-confirm="<?php _e('delete_page_confirm'); ?>">
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

<style>
.template-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: var(--border-radius-sm);
    font-size: var(--font-size-xs);
    background-color: var(--gray-200);
    color: var(--gray-700);
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

.order-handle {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.handle {
    cursor: move;
    color: var(--gray-400);
}

.sortable-ghost {
    background-color: var(--gray-100);
    opacity: 0.8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
    
    // Sortable functionality (if the library is loaded)
    if (typeof Sortable !== 'undefined') {
        const sortableTable = document.querySelector('.sortable');
        
        if (sortableTable) {
            const sortable = Sortable.create(sortableTable, {
                handle: '.handle',
                animation: 150,
                ghostClass: 'sortable-ghost',
                onEnd: function() {
                    updateOrder();
                }
            });
        }
    }
    
    // Update order function
    function updateOrder() {
        const rows = document.querySelectorAll('.sortable tr');
        const order = {};
        
        rows.forEach((row, index) => {
            const id = row.dataset.id;
            if (id) {
                order[id] = index;
                row.querySelector('.order-handle span').textContent = index;
            }
        });
        
        // Send order to server
        const url = document.querySelector('.sortable').dataset.action;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: 'order=' + JSON.stringify(order)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const toast = document.createElement('div');
                toast.className = 'toast toast-success';
                toast.innerHTML = `
                    <div class="toast-icon">
                        <i class="material-icons">check_circle</i>
                    </div>
                    <div class="toast-content">
                        <div class="toast-title">${data.message}</div>
                    </div>
                    <button class="toast-close">
                        <i class="material-icons">close</i>
                    </button>
                `;
                
                document.body.appendChild(toast);
                
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        toast.remove();
                    }, 300);
                }, 3000);
            }
        })
        .catch(error => {
            console.error('Error updating order:', error);
        });
    }
});
</script>