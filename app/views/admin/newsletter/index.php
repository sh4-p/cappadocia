<div class="page-header">
    <div class="page-title">
        <h1><i class="material-icons">email</i> <?php _e('newsletter'); ?></h1>
        <p><?php _e('newsletter_dashboard_description'); ?></p>
    </div>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/newsletter/add-subscriber" class="btn btn-primary">
            <i class="material-icons">person_add</i>
            <?php _e('add_subscriber'); ?>
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="material-icons text-primary">people</i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['total']); ?></h3>
                <p><?php _e('total_subscribers'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="material-icons text-success">check_circle</i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['active']); ?></h3>
                <p><?php _e('active_subscribers'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="material-icons text-warning">schedule</i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['pending']); ?></h3>
                <p><?php _e('pending_subscribers'); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="material-icons text-info">trending_up</i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['recent']); ?></h3>
                <p><?php _e('recent_subscriptions'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Subscribers -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4><i class="material-icons">people</i> <?php _e('recent_subscribers'); ?></h4>
                <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="btn btn-sm btn-outline-primary">
                    <?php _e('view_all'); ?>
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentSubscribers)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php _e('email'); ?></th>
                                    <th><?php _e('name'); ?></th>
                                    <th><?php _e('status'); ?></th>
                                    <th><?php _e('date'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentSubscribers as $subscriber): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($subscriber['email']); ?></td>
                                    <td><?php echo htmlspecialchars($subscriber['name'] ?: '-'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $subscriber['status'] === 'active' ? 'success' : ($subscriber['status'] === 'pending' ? 'warning' : 'secondary'); ?>">
                                            <?php _e('status_' . $subscriber['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($subscriber['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="material-icons">people_outline</i>
                        <h4><?php _e('no_subscribers_yet'); ?></h4>
                        <p><?php _e('no_subscribers_description'); ?></p>
                        <a href="<?php echo $adminUrl; ?>/newsletter/add-subscriber" class="btn btn-primary">
                            <?php _e('add_first_subscriber'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Recent Campaigns -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4><i class="material-icons">campaign</i> <?php _e('recent_campaigns'); ?></h4>
                <a href="<?php echo $adminUrl; ?>/newsletter/campaigns" class="btn btn-sm btn-outline-primary">
                    <?php _e('view_all'); ?>
                </a>
            </div>
            <div class="card-body">
                <?php if (!empty($recentCampaigns)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th><?php _e('subject'); ?></th>
                                    <th><?php _e('status'); ?></th>
                                    <th><?php _e('sent_count'); ?></th>
                                    <th><?php _e('date'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentCampaigns as $campaign): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($campaign['subject']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $campaign['status'] === 'sent' ? 'success' : ($campaign['status'] === 'draft' ? 'secondary' : 'info'); ?>">
                                            <?php _e('campaign_status_' . $campaign['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo number_format($campaign['sent_count'] ?? 0); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($campaign['created_at'])); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <i class="material-icons">campaign</i>
                        <h4><?php _e('no_campaigns_yet'); ?></h4>
                        <p><?php _e('no_campaigns_description'); ?></p>
                        <a href="<?php echo $adminUrl; ?>/newsletter/create-campaign" class="btn btn-primary">
                            <?php _e('create_first_campaign'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4><i class="material-icons">flash_on</i> <?php _e('quick_actions'); ?></h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <a href="<?php echo $adminUrl; ?>/newsletter/subscribers" class="quick-action-card">
                            <i class="material-icons">people</i>
                            <h5><?php _e('manage_subscribers'); ?></h5>
                            <p><?php _e('manage_subscribers_description'); ?></p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo $adminUrl; ?>/newsletter/campaigns" class="quick-action-card">
                            <i class="material-icons">campaign</i>
                            <h5><?php _e('manage_campaigns'); ?></h5>
                            <p><?php _e('manage_campaigns_description'); ?></p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo $adminUrl; ?>/newsletter/import-subscribers" class="quick-action-card">
                            <i class="material-icons">file_upload</i>
                            <h5><?php _e('import_subscribers'); ?></h5>
                            <p><?php _e('import_subscribers_description'); ?></p>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="<?php echo $adminUrl; ?>/newsletter/export-subscribers" class="quick-action-card">
                            <i class="material-icons">file_download</i>
                            <h5><?php _e('export_subscribers'); ?></h5>
                            <p><?php _e('export_subscribers_description'); ?></p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.stat-card {
    background: white;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    margin-top: 1rem;
}

.stat-icon {
    margin-right: 15px;
}

.stat-icon i {
    font-size: 36px;
}

.stat-content h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 600;
    color: #333;
}

.stat-content p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

.quick-action-card {
    display: block;
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    text-decoration: none;
    color: inherit;
    transition: all 0.3s ease;
    text-align: center;
    height: 100%;
}

.quick-action-card:hover {
    text-decoration: none;
    color: inherit;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.quick-action-card i {
    font-size: 36px;
    color: #4361ee;
    margin-bottom: 10px;
}

.quick-action-card h5 {
    margin: 10px 0;
    color: #333;
}

.quick-action-card p {
    color: #666;
    font-size: 14px;
    margin: 0;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state i {
    font-size: 64px;
    color: #ccc;
    margin-bottom: 20px;
}

.empty-state h4 {
    color: #666;
    margin-bottom: 10px;
}

.empty-state p {
    color: #999;
    margin-bottom: 20px;
}
</style>