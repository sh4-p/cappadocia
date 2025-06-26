<?php
/**
 * Admin Anti-Bot IP Blocks Management View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('ip_blocks_management'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/antibot" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_antibot'); ?></span>
        </a>
        <button type="button" class="btn btn-primary" onclick="addIPBlock()">
            <i class="material-icons">add</i>
            <span><?php _e('block_ip_address'); ?></span>
        </button>
    </div>
</div>

<!-- Summary Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="material-icons">block</i>
        </div>
        <div class="stat-content">
            <div class="stat-number"><?php echo number_format($totalBlocks); ?></div>
            <div class="stat-label"><?php _e('total_blocked_ips'); ?></div>
        </div>
    </div>
    
    <div class="stat-card stat-success">
        <div class="stat-icon">
            <i class="material-icons">check_circle</i>
        </div>
        <div class="stat-content">
            <div class="stat-number">
                <?php 
                $activeBlocks = array_filter($blocks, function($block) {
                    return $block['status'] === 'Active';
                });
                echo count($activeBlocks);
                ?>
            </div>
            <div class="stat-label"><?php _e('active_blocks'); ?></div>
        </div>
    </div>
    
    <div class="stat-card stat-warning">
        <div class="stat-icon">
            <i class="material-icons">schedule</i>
        </div>
        <div class="stat-content">
            <div class="stat-number">
                <?php 
                $expiredBlocks = array_filter($blocks, function($block) {
                    return $block['status'] === 'Expired';
                });
                echo count($expiredBlocks);
                ?>
            </div>
            <div class="stat-label"><?php _e('expired_blocks'); ?></div>
        </div>
    </div>
    
    <div class="stat-card stat-info">
        <div class="stat-icon">
            <i class="material-icons">all_inclusive</i>
        </div>
        <div class="stat-content">
            <div class="stat-number">
                <?php 
                $permanentBlocks = array_filter($blocks, function($block) {
                    return $block['status'] === 'Permanent';
                });
                echo count($permanentBlocks);
                ?>
            </div>
            <div class="stat-label"><?php _e('permanent_blocks'); ?></div>
        </div>
    </div>
</div>

<!-- IP Blocks List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?php _e('blocked_ip_addresses'); ?>
            <?php if ($totalBlocks > 0): ?>
                <span class="badge badge-danger"><?php echo number_format($totalBlocks); ?></span>
            <?php endif; ?>
        </h3>
        <div class="card-actions">
            <?php if (!empty($blocks)): ?>
                <button type="button" class="btn btn-sm btn-warning" onclick="cleanExpiredBlocks()">
                    <i class="material-icons">cleaning_services</i>
                    <?php _e('clean_expired'); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($blocks)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">block</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_blocked_ips'); ?></h3>
                <p class="empty-state-description"><?php _e('no_blocked_ips_description'); ?></p>
                <button type="button" class="btn btn-primary" onclick="addIPBlock()">
                    <i class="material-icons">add</i>
                    <?php _e('block_first_ip'); ?>
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover blocks-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll(this)">
                            </th>
                            <th><?php _e('ip_address'); ?></th>
                            <th><?php _e('reason'); ?></th>
                            <th><?php _e('status'); ?></th>
                            <th><?php _e('created_at'); ?></th>
                            <th><?php _e('expires_at'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($blocks as $block): ?>
                            <tr class="block-row" data-status="<?php echo $block['status']; ?>">
                                <td>
                                    <input type="checkbox" class="block-checkbox" value="<?php echo $block['id']; ?>">
                                </td>
                                <td>
                                    <div class="ip-info">
                                        <span class="ip-address" data-ip="<?php echo $block['ip_address']; ?>">
                                            <?php echo $block['ip_address']; ?>
                                        </span>
                                        <button type="button" class="btn-link btn-sm" onclick="lookupIP('<?php echo $block['ip_address']; ?>')" title="<?php _e('lookup_ip'); ?>">
                                            <i class="material-icons">search</i>
                                        </button>
                                    </div>
                                </td>
                                <td>
                                    <div class="reason">
                                        <?php echo $block['reason'] ? htmlspecialchars($block['reason']) : '<em>' . __('no_reason_provided') . '</em>'; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($block['status']); ?>">
                                        <?php 
                                        switch($block['status']) {
                                            case 'Active':
                                                _e('active');
                                                break;
                                            case 'Expired':
                                                _e('expired');
                                                break;
                                            case 'Permanent':
                                                _e('permanent');
                                                break;
                                            default:
                                                echo $block['status'];
                                        }
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="datetime">
                                        <div class="date"><?php echo date('M d, Y', strtotime($block['created_at'])); ?></div>
                                        <div class="time"><?php echo date('H:i:s', strtotime($block['created_at'])); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($block['expires_at']): ?>
                                        <div class="datetime">
                                            <div class="date"><?php echo date('M d, Y', strtotime($block['expires_at'])); ?></div>
                                            <div class="time"><?php echo date('H:i:s', strtotime($block['expires_at'])); ?></div>
                                        </div>
                                        <?php if (strtotime($block['expires_at']) > time()): ?>
                                            <small class="text-muted">
                                                <?php 
                                                $timeLeft = strtotime($block['expires_at']) - time();
                                                if ($timeLeft > 3600) {
                                                    echo sprintf(__('expires_in_hours'), ceil($timeLeft / 3600));
                                                } else {
                                                    echo sprintf(__('expires_in_minutes'), ceil($timeLeft / 60));
                                                }
                                                ?>
                                            </small>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted"><?php _e('never_expires'); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="actions">
                                        <button type="button" class="action-btn" title="<?php _e('view_details'); ?>" 
                                                onclick="viewBlockDetails(<?php echo $block['id']; ?>)">
                                            <i class="material-icons">visibility</i>
                                        </button>
                                        <?php if ($block['status'] === 'Active' || $block['status'] === 'Permanent'): ?>
                                            <button type="button" class="action-btn action-warning" title="<?php _e('extend_block'); ?>" 
                                                    onclick="extendBlock(<?php echo $block['id']; ?>, '<?php echo $block['ip_address']; ?>')">
                                                <i class="material-icons">schedule</i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="action-btn action-danger" title="<?php _e('remove_block'); ?>" 
                                                onclick="removeBlock(<?php echo $block['id']; ?>, '<?php echo $block['ip_address']; ?>')">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Bulk Actions -->
            <div class="bulk-actions">
                <div class="bulk-actions-left">
                    <span id="selected-count">0</span> <?php _e('selected'); ?>
                </div>
                <div class="bulk-actions-right">
                    <button type="button" class="btn btn-sm btn-danger" onclick="bulkRemoveBlocks()" disabled id="bulk-remove-btn">
                        <i class="material-icons">delete</i>
                        <?php _e('remove_selected'); ?>
                    </button>
                </div>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper">
                    <nav aria-label="<?php _e('pagination'); ?>">
                        <ul class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo $adminUrl; ?>/antibot/blocks?page=<?php echo $currentPage - 1; ?>">
                                        <i class="material-icons">chevron_left</i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($totalPages, $currentPage + 2);
                            ?>
                            
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="<?php echo $adminUrl; ?>/antibot/blocks?page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo $adminUrl; ?>/antibot/blocks?page=<?php echo $currentPage + 1; ?>">
                                        <i class="material-icons">chevron_right</i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Add IP Block Modal -->
<div class="modal fade" id="addIPBlockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('block_ip_address'); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?php echo $adminUrl; ?>/antibot/add-block" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ip_address"><?php _e('ip_address'); ?> <span class="required">*</span></label>
                        <input type="text" id="ip_address" name="ip_address" class="form-control" required 
                               placeholder="192.168.1.1" pattern="^(?:[0-9]{1,3}\.){3}[0-9]{1,3}$">
                        <small class="form-text"><?php _e('enter_valid_ipv4_address'); ?></small>
                    </div>
                    
                    <div class="form-group">
                        <label for="reason"><?php _e('reason'); ?></label>
                        <input type="text" id="reason" name="reason" class="form-control" 
                               placeholder="<?php _e('manual_block'); ?>" maxlength="255">
                    </div>
                    
                    <div class="form-group">
                        <label for="hours"><?php _e('block_duration_hours'); ?></label>
                        <select id="hours" name="hours" class="form-select">
                            <option value="0"><?php _e('permanent'); ?></option>
                            <option value="1">1 <?php _e('hour'); ?></option>
                            <option value="6">6 <?php _e('hours'); ?></option>
                            <option value="24" selected>24 <?php _e('hours'); ?></option>
                            <option value="168">7 <?php _e('days'); ?></option>
                            <option value="720">30 <?php _e('days'); ?></option>
                        </select>
                        <small class="form-text"><?php _e('select_block_duration'); ?></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><?php _e('cancel'); ?></button>
                    <button type="submit" class="btn btn-danger"><?php _e('block_ip'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Extend Block Modal -->
<div class="modal fade" id="extendBlockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('extend_block'); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="extend-block-form" method="post">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="material-icons">info</i>
                        <?php _e('extending_block_for'); ?> <strong id="extend-ip"></strong>
                    </div>
                    
                    <div class="form-group">
                        <label for="extend_hours"><?php _e('extend_by_hours'); ?></label>
                        <select id="extend_hours" name="hours" class="form-select">
                            <option value="1">1 <?php _e('hour'); ?></option>
                            <option value="6">6 <?php _e('hours'); ?></option>
                            <option value="24" selected>24 <?php _e('hours'); ?></option>
                            <option value="168">7 <?php _e('days'); ?></option>
                            <option value="720">30 <?php _e('days'); ?></option>
                            <option value="0"><?php _e('make_permanent'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal"><?php _e('cancel'); ?></button>
                    <button type="submit" class="btn btn-warning"><?php _e('extend_block'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Block Details Modal -->
<div class="modal fade" id="blockDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('block_details'); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="block-details-content">
                    <div class="loading"><?php _e('loading'); ?>...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php _e('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: white;
    border-radius: 8px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid var(--primary-color);
}

.stat-card.stat-success {
    border-left-color: #28a745;
}

.stat-card.stat-warning {
    border-left-color: #ffc107;
}

.stat-card.stat-info {
    border-left-color: #17a2b8;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--primary-color);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-card.stat-success .stat-icon {
    background: #28a745;
}

.stat-card.stat-warning .stat-icon {
    background: #ffc107;
}

.stat-card.stat-info .stat-icon {
    background: #17a2b8;
}

.stat-number {
    font-size: 2rem;
    font-weight: bold;
    color: #333;
    line-height: 1;
}

.stat-label {
    color: #666;
    font-size: 0.9rem;
}

.ip-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.ip-address {
    font-family: monospace;
    font-weight: 500;
    cursor: pointer;
}

.ip-address:hover {
    color: var(--primary-color);
}

.btn-link {
    background: none;
    border: none;
    color: #6c757d;
    padding: 0.25rem;
    text-decoration: none;
}

.btn-link:hover {
    color: var(--primary-color);
}

.reason {
    max-width: 200px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.status-active {
    background-color: #28a745;
    color: white;
}

.status-expired {
    background-color: #6c757d;
    color: white;
}

.status-permanent {
    background-color: #17a2b8;
    color: white;
}

.datetime {
    font-size: 0.9rem;
}

.datetime .date {
    font-weight: 500;
}

.datetime .time {
    color: #666;
    font-size: 0.8rem;
}

.actions {
    display: flex;
    gap: 0.25rem;
}

.action-btn {
    background: none;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    padding: 0.25rem;
    cursor: pointer;
    color: #666;
    transition: all 0.3s;
}

.action-btn:hover {
    background-color: #f8f9fa;
    color: var(--primary-color);
}

.action-btn.action-warning:hover {
    background-color: #ffc107;
    color: white;
    border-color: #ffc107;
}

.action-btn.action-danger:hover {
    background-color: #dc3545;
    color: white;
    border-color: #dc3545;
}

.bulk-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-top: 1px solid #dee2e6;
    margin-top: 1rem;
}

.bulk-actions-left {
    color: #666;
    font-size: 0.9rem;
}

.block-row.expired {
    opacity: 0.6;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-state-icon i {
    font-size: 4rem;
    color: #ccc;
    margin-bottom: 1rem;
}

.empty-state-title {
    color: #666;
    margin-bottom: 1rem;
}

.empty-state-description {
    color: #999;
    margin-bottom: 2rem;
}

.pagination-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

.loading {
    text-align: center;
    padding: 2rem;
    color: #666;
}

#block-details-content .detail-row {
    display: flex;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

#block-details-content .detail-label {
    flex: 0 0 150px;
    font-weight: 600;
    color: #333;
}

#block-details-content .detail-value {
    flex: 1;
    font-family: monospace;
    word-break: break-all;
}
</style>

<script>
let selectedBlocks = new Set();

function addIPBlock() {
    $('#addIPBlockModal').modal('show');
}

function toggleSelectAll(checkbox) {
    const checkboxes = document.querySelectorAll('.block-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = checkbox.checked;
        if (checkbox.checked) {
            selectedBlocks.add(cb.value);
        } else {
            selectedBlocks.delete(cb.value);
        }
    });
    updateBulkActions();
}

function updateBulkActions() {
    const count = selectedBlocks.size;
    document.getElementById('selected-count').textContent = count;
    document.getElementById('bulk-remove-btn').disabled = count === 0;
}

function viewBlockDetails(blockId) {
    const modal = document.getElementById('blockDetailsModal');
    const content = document.getElementById('block-details-content');
    
    // Find the block in the current page data
    const blocks = <?php echo json_encode($blocks); ?>;
    const block = blocks.find(b => b.id == blockId);
    
    if (block) {
        content.innerHTML = `
            <div class="detail-row">
                <div class="detail-label"><?php _e('id'); ?>:</div>
                <div class="detail-value">${block.id}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('ip_address'); ?>:</div>
                <div class="detail-value">${block.ip_address}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('reason'); ?>:</div>
                <div class="detail-value">${block.reason || '<?php _e("no_reason_provided"); ?>'}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('status'); ?>:</div>
                <div class="detail-value">${block.status}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('created_at'); ?>:</div>
                <div class="detail-value">${block.created_at}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('expires_at'); ?>:</div>
                <div class="detail-value">${block.expires_at || '<?php _e("never"); ?>'}</div>
            </div>
        `;
    }
    
    $('#blockDetailsModal').modal('show');
}

function extendBlock(blockId, ipAddress) {
    document.getElementById('extend-ip').textContent = ipAddress;
    document.getElementById('extend-block-form').action = `<?php echo $adminUrl; ?>/antibot/extend-block/${blockId}`;
    $('#extendBlockModal').modal('show');
}

function removeBlock(blockId, ipAddress) {
    if (confirm(`<?php _e("remove_block_confirm"); ?> ${ipAddress}?`)) {
        window.location.href = `<?php echo $adminUrl; ?>/antibot/remove-block/${blockId}`;
    }
}

function bulkRemoveBlocks() {
    if (selectedBlocks.size === 0) return;
    
    if (confirm(`<?php _e("remove_selected_blocks_confirm"); ?> ${selectedBlocks.size} <?php _e("blocks"); ?>?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo $adminUrl; ?>/antibot/bulk-remove-blocks';
        
        selectedBlocks.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'block_ids[]';
            input.value = id;
            form.appendChild(input);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

function cleanExpiredBlocks() {
    if (confirm('<?php _e("clean_expired_blocks_confirm"); ?>')) {
        window.location.href = '<?php echo $adminUrl; ?>/antibot/clean-expired';
    }
}

function lookupIP(ipAddress) {
    // Open IP lookup in new window
    window.open(`https://whatismyipaddress.com/ip/${ipAddress}`, '_blank');
}

// Handle individual checkbox changes
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('block-checkbox')) {
        if (e.target.checked) {
            selectedBlocks.add(e.target.value);
        } else {
            selectedBlocks.delete(e.target.value);
        }
        updateBulkActions();
        
        // Update select all checkbox
        const allCheckboxes = document.querySelectorAll('.block-checkbox');
        const checkedCheckboxes = document.querySelectorAll('.block-checkbox:checked');
        const selectAllCheckbox = document.getElementById('select-all');
        
        if (checkedCheckboxes.length === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (checkedCheckboxes.length === allCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
});

// Copy IP address to clipboard when clicked
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('ip-address')) {
        const ip = e.target.dataset.ip;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(ip).then(function() {
                e.target.title = '<?php _e("copied_to_clipboard"); ?>';
                setTimeout(() => {
                    e.target.title = ip;
                }, 2000);
            });
        }
    }
});

// Initialize
updateBulkActions();
</script>