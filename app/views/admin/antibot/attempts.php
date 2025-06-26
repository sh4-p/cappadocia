<?php
/**
 * Admin Anti-Bot Attempts Log View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('bot_attempts_log'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/antibot" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_antibot'); ?></span>
        </a>
        <?php if (!empty($attempts)): ?>
            <a href="<?php echo $adminUrl; ?>/antibot/export?<?php echo http_build_query($filters); ?>" class="btn btn-success">
                <i class="material-icons">download</i>
                <span><?php _e('export_csv'); ?></span>
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filters -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="material-icons">filter_list</i>
            <?php _e('filters'); ?>
        </h3>
        <div class="card-actions">
            <button type="button" class="btn btn-sm btn-light" onclick="clearFilters()">
                <i class="material-icons">clear</i>
                <?php _e('clear_filters'); ?>
            </button>
        </div>
    </div>
    <div class="card-body">
        <form method="get" action="<?php echo $adminUrl; ?>/antibot/attempts" class="filters-form">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="protection_type"><?php _e('protection_type'); ?></label>
                        <select id="protection_type" name="protection_type" class="form-select">
                            <option value=""><?php _e('all_types'); ?></option>
                            <?php foreach ($protectionTypes as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo $filters['protection_type'] === $type ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="form_type"><?php _e('form_type'); ?></label>
                        <select id="form_type" name="form_type" class="form-select">
                            <option value=""><?php _e('all_forms'); ?></option>
                            <?php foreach ($formTypes as $type): ?>
                                <option value="<?php echo $type; ?>" <?php echo $filters['form_type'] === $type ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($type); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date_from"><?php _e('date_from'); ?></label>
                        <input type="date" id="date_from" name="date_from" class="form-control" 
                               value="<?php echo htmlspecialchars($filters['date_from'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="date_to"><?php _e('date_to'); ?></label>
                        <input type="date" id="date_to" name="date_to" class="form-control" 
                               value="<?php echo htmlspecialchars($filters['date_to'] ?? ''); ?>">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">
                            <i class="material-icons">search</i>
                            <?php _e('filter'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Results -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <?php _e('bot_attempts'); ?>
            <?php if ($totalAttempts > 0): ?>
                <span class="badge badge-danger"><?php echo number_format($totalAttempts); ?></span>
            <?php endif; ?>
        </h3>
        <div class="card-actions">
            <span class="results-info">
                <?php if ($totalAttempts > 0): ?>
                    <?php echo sprintf(__('showing_results'), ($currentPage - 1) * 50 + 1, min($currentPage * 50, $totalAttempts), $totalAttempts); ?>
                <?php endif; ?>
            </span>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($attempts)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="material-icons">security</i>
                </div>
                <h3 class="empty-state-title"><?php _e('no_bot_attempts_found'); ?></h3>
                <p class="empty-state-description">
                    <?php if (array_filter($filters)): ?>
                        <?php _e('no_bot_attempts_with_filters'); ?>
                    <?php else: ?>
                        <?php _e('no_bot_attempts_description'); ?>
                    <?php endif; ?>
                </p>
                <?php if (array_filter($filters)): ?>
                    <button type="button" class="btn btn-primary" onclick="clearFilters()">
                        <?php _e('clear_filters'); ?>
                    </button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover attempts-table">
                    <thead>
                        <tr>
                            <th><?php _e('protection_type'); ?></th>
                            <th><?php _e('ip_address'); ?></th>
                            <th><?php _e('form_type'); ?></th>
                            <th><?php _e('user_agent'); ?></th>
                            <th><?php _e('date_time'); ?></th>
                            <th><?php _e('actions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attempts as $attempt): ?>
                            <tr>
                                <td>
                                    <span class="protection-type-badge protection-<?php echo $attempt['protection_type']; ?>">
                                        <?php echo ucfirst($attempt['protection_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="ip-address" data-ip="<?php echo $attempt['ip_address']; ?>">
                                        <?php echo $attempt['ip_address']; ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="form-type-badge">
                                        <?php echo ucfirst($attempt['form_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="user-agent" title="<?php echo htmlspecialchars($attempt['user_agent']); ?>">
                                        <?php echo $this->truncateText($attempt['user_agent'], 50); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="datetime">
                                        <div class="date"><?php echo date('M d, Y', strtotime($attempt['created_at'])); ?></div>
                                        <div class="time"><?php echo date('H:i:s', strtotime($attempt['created_at'])); ?></div>
                                    </div>
                                </td>
                                <td>
                                    <div class="actions">
                                        <button type="button" class="action-btn" title="<?php _e('view_details'); ?>" 
                                                onclick="viewAttemptDetails(<?php echo $attempt['id']; ?>)">
                                            <i class="material-icons">visibility</i>
                                        </button>
                                        <button type="button" class="action-btn action-danger" title="<?php _e('block_ip'); ?>" 
                                                onclick="blockIP('<?php echo $attempt['ip_address']; ?>')">
                                            <i class="material-icons">block</i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination-wrapper">
                    <nav aria-label="<?php _e('pagination'); ?>">
                        <ul class="pagination">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo $adminUrl; ?>/antibot/attempts?<?php echo http_build_query(array_merge($filters, ['page' => $currentPage - 1])); ?>">
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
                                    <a class="page-link" href="<?php echo $adminUrl; ?>/antibot/attempts?<?php echo http_build_query(array_merge($filters, ['page' => $i])); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?php echo $adminUrl; ?>/antibot/attempts?<?php echo http_build_query(array_merge($filters, ['page' => $currentPage + 1])); ?>">
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

<!-- View Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php _e('attempt_details'); ?></h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="attempt-details-content">
                    <div class="loading"><?php _e('loading'); ?>...</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-dismiss="modal"><?php _e('close'); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Block IP Modal -->
<div class="modal fade" id="blockIPModal" tabindex="-1">
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
                        <label for="block_ip_address"><?php _e('ip_address'); ?></label>
                        <input type="text" id="block_ip_address" name="ip_address" class="form-control" readonly>
                    </div>
                    
                    <div class="form-group">
                        <label for="block_reason"><?php _e('reason'); ?></label>
                        <input type="text" id="block_reason" name="reason" class="form-control" 
                               value="<?php _e('blocked_due_to_bot_activity'); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="block_hours"><?php _e('block_duration_hours'); ?></label>
                        <input type="number" id="block_hours" name="hours" class="form-control" value="24" min="0">
                        <small class="form-text"><?php _e('leave_empty_permanent_block'); ?></small>
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

<style>
.filters-form {
    margin: 0;
}

.results-info {
    font-size: 0.9rem;
    color: #666;
}

.attempts-table th {
    border-top: none;
    font-weight: 600;
}

.protection-type-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    text-transform: uppercase;
}

.protection-honeypot {
    background-color: #e3f2fd;
    color: #1976d2;
}

.protection-recaptcha_v2 {
    background-color: #f3e5f5;
    color: #7b1fa2;
}

.protection-recaptcha_v3 {
    background-color: #e8f5e8;
    color: #388e3c;
}

.protection-turnstile {
    background-color: #fff3e0;
    color: #f57c00;
}

.protection-rate_limit {
    background-color: #ffebee;
    color: #d32f2f;
}

.form-type-badge {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    color: #495057;
}

.ip-address {
    font-family: monospace;
    font-weight: 500;
    cursor: pointer;
}

.ip-address:hover {
    color: var(--primary-color);
}

.user-agent {
    font-family: monospace;
    font-size: 0.8rem;
    color: #666;
    max-width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
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

.action-btn.action-danger:hover {
    background-color: #dc3545;
    color: white;
    border-color: #dc3545;
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

.pagination {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
}

.page-item {
    margin: 0 0.125rem;
}

.page-link {
    display: block;
    padding: 0.5rem 0.75rem;
    color: var(--primary-color);
    text-decoration: none;
    border: 1px solid #dee2e6;
    border-radius: 0.25rem;
    transition: all 0.3s;
}

.page-link:hover,
.page-item.active .page-link {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.loading {
    text-align: center;
    padding: 2rem;
    color: #666;
}

#attempt-details-content .detail-row {
    display: flex;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f0f0f0;
}

#attempt-details-content .detail-label {
    flex: 0 0 150px;
    font-weight: 600;
    color: #333;
}

#attempt-details-content .detail-value {
    flex: 1;
    font-family: monospace;
    word-break: break-all;
}
</style>

<script>
function clearFilters() {
    window.location.href = '<?php echo $adminUrl; ?>/antibot/attempts';
}

function viewAttemptDetails(attemptId) {
    // In a real implementation, you would fetch attempt details via AJAX
    const modal = document.getElementById('detailsModal');
    const content = document.getElementById('attempt-details-content');
    
    // Find the attempt in the current page data
    const attempts = <?php echo json_encode($attempts); ?>;
    const attempt = attempts.find(a => a.id == attemptId);
    
    if (attempt) {
        content.innerHTML = `
            <div class="detail-row">
                <div class="detail-label"><?php _e('id'); ?>:</div>
                <div class="detail-value">${attempt.id}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('protection_type'); ?>:</div>
                <div class="detail-value">${attempt.protection_type}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('ip_address'); ?>:</div>
                <div class="detail-value">${attempt.ip_address}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('form_type'); ?>:</div>
                <div class="detail-value">${attempt.form_type}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('user_agent'); ?>:</div>
                <div class="detail-value">${attempt.user_agent}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label"><?php _e('created_at'); ?>:</div>
                <div class="detail-value">${attempt.created_at}</div>
            </div>
        `;
    }
    
    $('#detailsModal').modal('show');
}

function blockIP(ipAddress) {
    document.getElementById('block_ip_address').value = ipAddress;
    $('#blockIPModal').modal('show');
}

// Copy IP address to clipboard when clicked
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('ip-address')) {
        const ip = e.target.dataset.ip;
        if (navigator.clipboard) {
            navigator.clipboard.writeText(ip).then(function() {
                // Show tooltip or notification
                e.target.title = '<?php _e("copied_to_clipboard"); ?>';
                setTimeout(() => {
                    e.target.title = ip;
                }, 2000);
            });
        }
    }
});

<?php
// Helper function to truncate text
function truncateText($text, $length = 50, $suffix = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}
// Make the function available in the view context
$this->truncateText = 'truncateText';
?>
</script>