<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">campaign</i> <?php _e('newsletter_campaigns'); ?></h1>
        <p><?php _e('manage_newsletter_campaigns'); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/newsletter" class="btn btn-outline-secondary">
            <i class="material-icons">dashboard</i>
            <?php _e('newsletter_dashboard'); ?>
        </a>
        <a href="<?php echo $adminUrl; ?>/newsletter/create-campaign" class="btn btn-primary">
            <i class="material-icons">add</i>
            <?php _e('create_campaign'); ?>
        </a>
    </div>
</div>

<?php if (!empty($campaigns)): ?>
<div class="card">
    <div class="card-header">
        <h4><?php _e('campaigns'); ?> (<?php echo number_format($totalCampaigns); ?>)</h4>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th><?php _e('subject'); ?></th>
                        <th><?php _e('status'); ?></th>
                        <th><?php _e('recipients'); ?></th>
                        <th><?php _e('sent_count'); ?></th>
                        <th><?php _e('failed_count'); ?></th>
                        <th><?php _e('created_by'); ?></th>
                        <th><?php _e('created_at'); ?></th>
                        <th><?php _e('sent_at'); ?></th>
                        <th width="150"><?php _e('actions'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campaigns as $campaign): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($campaign['subject']); ?></strong>
                            <?php if ($campaign['localized_subject'] && $campaign['localized_subject'] !== $campaign['subject']): ?>
                                <br><small class="text-muted"><?php echo htmlspecialchars($campaign['localized_subject']); ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge badge-<?php 
                                echo $campaign['status'] === 'sent' ? 'success' : 
                                    ($campaign['status'] === 'draft' ? 'secondary' : 
                                    ($campaign['status'] === 'sending' ? 'info' : 'danger')); 
                            ?>">
                                <?php _e('campaign_status_' . $campaign['status']); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($campaign['total_recipients']): ?>
                                <span class="badge badge-info"><?php echo number_format($campaign['total_recipients']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($campaign['sent_count']): ?>
                                <span class="text-success"><?php echo number_format($campaign['sent_count']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($campaign['failed_count']): ?>
                                <span class="text-danger"><?php echo number_format($campaign['failed_count']); ?></span>
                            <?php else: ?>
                                <span class="text-muted">0</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($campaign['first_name'] && $campaign['last_name']): ?>
                                <?php echo htmlspecialchars($campaign['first_name'] . ' ' . $campaign['last_name']); ?>
                            <?php else: ?>
                                <span class="text-muted"><?php _e('unknown'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo date('d/m/Y H:i', strtotime($campaign['created_at'])); ?></td>
                        <td>
                            <?php if ($campaign['sent_at']): ?>
                                <?php echo date('d/m/Y H:i', strtotime($campaign['sent_at'])); ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="<?php echo $adminUrl; ?>/newsletter/view-campaign/<?php echo $campaign['id']; ?>" 
                                   class="btn btn-sm btn-outline-info" title="<?php _e('view'); ?>">
                                    <i class="material-icons">visibility</i>
                                </a>
                                
                                <?php if ($campaign['status'] === 'draft'): ?>
                                <a href="<?php echo $adminUrl; ?>/newsletter/edit-campaign/<?php echo $campaign['id']; ?>" 
                                   class="btn btn-sm btn-outline-primary" title="<?php _e('edit'); ?>">
                                    <i class="material-icons">edit</i>
                                </a>
                                <a href="<?php echo $adminUrl; ?>/newsletter/send-campaign/<?php echo $campaign['id']; ?>" 
                                   class="btn btn-sm btn-outline-success" 
                                   title="<?php _e('send_campaign'); ?>"
                                   onclick="return confirm('<?php _e('confirm_send_campaign'); ?>')">
                                    <i class="material-icons">send</i>
                                </a>
                                <a href="<?php echo $adminUrl; ?>/newsletter/delete-campaign/<?php echo $campaign['id']; ?>" 
                                   class="btn btn-sm btn-outline-danger" 
                                   title="<?php _e('delete'); ?>"
                                   onclick="return confirm('<?php _e('confirm_delete_campaign'); ?>')">
                                    <i class="material-icons">delete</i>
                                </a>
                                <?php elseif ($campaign['status'] === 'sent'): ?>
                                <a href="<?php echo $adminUrl; ?>/newsletter/campaign-stats/<?php echo $campaign['id']; ?>" 
                                   class="btn btn-sm btn-outline-info" title="<?php _e('view_stats'); ?>">
                                    <i class="material-icons">analytics</i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
<?php if ($totalPages > 1): ?>
<div class="pagination-wrapper mt-4">
    <nav aria-label="<?php _e('pagination'); ?>">
        <ul class="pagination justify-content-center">
            <?php if ($currentPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage - 1; ?>">
                        <?php _e('previous'); ?>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php
            $start = max(1, $currentPage - 2);
            $end = min($totalPages, $currentPage + 2);
            ?>
            
            <?php for ($i = $start; $i <= $end; $i++): ?>
                <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            
            <?php if ($currentPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo $currentPage + 1; ?>">
                        <?php _e('next'); ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
<?php endif; ?>

<?php else: ?>
<!-- Empty State -->
<div class="card">
    <div class="card-body text-center py-5">
        <i class="material-icons" style="font-size: 64px; color: #ccc;">campaign</i>
        <h4 class="mt-3"><?php _e('no_campaigns_yet'); ?></h4>
        <p class="text-muted"><?php _e('no_campaigns_description'); ?></p>
        <a href="<?php echo $adminUrl; ?>/newsletter/create-campaign" class="btn btn-primary">
            <i class="material-icons">add</i>
            <?php _e('create_first_campaign'); ?>
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Campaign Status Info -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5><i class="material-icons">info</i> <?php _e('campaign_status_info'); ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="status-info">
                            <span class="badge badge-secondary"><?php _e('draft'); ?></span>
                            <p class="small text-muted mt-2"><?php _e('draft_status_description'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="status-info">
                            <span class="badge badge-info"><?php _e('sending'); ?></span>
                            <p class="small text-muted mt-2"><?php _e('sending_status_description'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="status-info">
                            <span class="badge badge-success"><?php _e('sent'); ?></span>
                            <p class="small text-muted mt-2"><?php _e('sent_status_description'); ?></p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="status-info">
                            <span class="badge badge-danger"><?php _e('failed'); ?></span>
                            <p class="small text-muted mt-2"><?php _e('failed_status_description'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.status-info {
    text-align: center;
}

.status-info .badge {
    font-size: 0.9rem;
    padding: 6px 12px;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}
</style>