<?php
/**
 * Admin Anti-Bot Statistics View
 */
?>

<div class="page-header">
    <h1 class="page-title"><?php _e('antibot_statistics'); ?></h1>
    <div class="page-actions">
        <a href="<?php echo $adminUrl; ?>/antibot" class="btn btn-light">
            <i class="material-icons">arrow_back</i>
            <span><?php _e('back_to_antibot'); ?></span>
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-light dropdown-toggle" data-toggle="dropdown">
                <i class="material-icons">date_range</i>
                <span>
                    <?php 
                    switch($days) {
                        case 7: _e('last_7_days'); break;
                        case 30: _e('last_30_days'); break;
                        case 90: _e('last_90_days'); break;
                        default: echo $days . ' ' . __('days');
                    }
                    ?>
                </span>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="<?php echo $adminUrl; ?>/antibot/statistics?days=7">
                    <?php _e('last_7_days'); ?>
                </a>
                <a class="dropdown-item" href="<?php echo $adminUrl; ?>/antibot/statistics?days=30">
                    <?php _e('last_30_days'); ?>
                </a>
                <a class="dropdown-item" href="<?php echo $adminUrl; ?>/antibot/statistics?days=90">
                    <?php _e('last_90_days'); ?>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Statistics -->
<div class="stats-summary">
    <div class="row">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-primary">
                    <i class="material-icons">trending_up</i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php 
                        $totalAttempts = array_sum(array_column($stats['daily_attempts'], 'total_attempts'));
                        echo number_format($totalAttempts);
                        ?>
                    </div>
                    <div class="stat-label"><?php _e('total_form_attempts'); ?></div>
                    <div class="stat-period"><?php echo sprintf(__('in_last_days'), $days); ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-danger">
                    <i class="material-icons">security</i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php 
                        $totalBotAttempts = array_sum(array_column($stats['daily_bot_attempts'], 'bot_attempts'));
                        echo number_format($totalBotAttempts);
                        ?>
                    </div>
                    <div class="stat-label"><?php _e('bot_attempts_blocked'); ?></div>
                    <div class="stat-period"><?php echo sprintf(__('in_last_days'), $days); ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-success">
                    <i class="material-icons">verified</i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php 
                        $successfulAttempts = array_sum(array_column($stats['daily_attempts'], 'successful_attempts'));
                        echo number_format($successfulAttempts);
                        ?>
                    </div>
                    <div class="stat-label"><?php _e('successful_submissions'); ?></div>
                    <div class="stat-period"><?php echo sprintf(__('in_last_days'), $days); ?></div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon stat-warning">
                    <i class="material-icons">trending_down</i>
                </div>
                <div class="stat-content">
                    <div class="stat-number">
                        <?php 
                        if ($totalAttempts > 0) {
                            $blockRate = ($totalBotAttempts / $totalAttempts) * 100;
                        } else {
                            $blockRate = 0;
                        }
                        echo number_format($blockRate, 1) . '%';
                        ?>
                    </div>
                    <div class="stat-label"><?php _e('block_rate'); ?></div>
                    <div class="stat-period"><?php echo sprintf(__('in_last_days'), $days); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row">
    <!-- Daily Activity Chart -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="material-icons">show_chart</i>
                    <?php _e('daily_activity'); ?>
                </h3>
            </div>
            <div class="card-body">
                <canvas id="dailyActivityChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Protection Methods -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="material-icons">pie_chart</i>
                    <?php _e('protection_methods'); ?>
                </h3>
            </div>
            <div class="card-body">
                <canvas id="protectionMethodsChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Form Types -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="material-icons">donut_small</i>
                    <?php _e('blocked_form_types'); ?>
                </h3>
            </div>
            <div class="card-body">
                <canvas id="formTypesChart" height="250"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Blocked IPs -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="material-icons">block</i>
                    <?php _e('top_blocked_ips'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?php if (empty($stats['form_attempts'])): ?>
                    <div class="empty-chart">
                        <i class="material-icons">block</i>
                        <p><?php _e('no_blocked_ips_in_period'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="top-ips-list">
                        <?php 
                        // Get top IPs from bot attempts
                        $ipCounts = [];
                        foreach ($stats['daily_bot_attempts'] as $attempt) {
                            // This would need to be implemented properly with IP grouping
                            // For now, showing placeholder data
                        }
                        
                        // Placeholder top IPs for demonstration
                        $topIPs = [
                            ['ip' => '192.168.1.100', 'attempts' => 25],
                            ['ip' => '10.0.0.50', 'attempts' => 18],
                            ['ip' => '172.16.0.75', 'attempts' => 12],
                            ['ip' => '203.0.113.5', 'attempts' => 8],
                            ['ip' => '198.51.100.25', 'attempts' => 6]
                        ];
                        ?>
                        
                        <?php foreach ($topIPs as $index => $ipData): ?>
                            <div class="top-ip-item">
                                <div class="ip-rank"><?php echo $index + 1; ?></div>
                                <div class="ip-info">
                                    <div class="ip-address"><?php echo $ipData['ip']; ?></div>
                                    <div class="ip-attempts"><?php echo $ipData['attempts']; ?> <?php _e('attempts'); ?></div>
                                </div>
                                <div class="ip-actions">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="blockIP('<?php echo $ipData['ip']; ?>')">
                                        <i class="material-icons">block</i>
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics Tables -->
<div class="row">
    <!-- Protection Breakdown -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="material-icons">shield</i>
                    <?php _e('protection_breakdown'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?php if (empty($stats['protection_breakdown'])): ?>
                    <div class="empty-table">
                        <i class="material-icons">shield</i>
                        <p><?php _e('no_protection_data'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><?php _e('protection_type'); ?></th>
                                    <th><?php _e('blocks'); ?></th>
                                    <th><?php _e('percentage'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalProtectionBlocks = array_sum(array_column($stats['protection_breakdown'], 'total'));
                                foreach ($stats['protection_breakdown'] as $protection): 
                                    $percentage = $totalProtectionBlocks > 0 ? ($protection['total'] / $totalProtectionBlocks) * 100 : 0;
                                ?>
                                    <tr>
                                        <td>
                                            <span class="protection-type-badge protection-<?php echo $protection['protection_type']; ?>">
                                                <?php echo ucfirst($protection['protection_type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($protection['total']); ?></td>
                                        <td>
                                            <div class="progress-bar-container">
                                                <div class="progress-bar" style="width: <?php echo $percentage; ?>%"></div>
                                                <span class="progress-text"><?php echo number_format($percentage, 1); ?>%</span>
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
    </div>
    
    <!-- Form Breakdown -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="material-icons">description</i>
                    <?php _e('form_breakdown'); ?>
                </h3>
            </div>
            <div class="card-body">
                <?php if (empty($stats['form_breakdown'])): ?>
                    <div class="empty-table">
                        <i class="material-icons">description</i>
                        <p><?php _e('no_form_data'); ?></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th><?php _e('form_type'); ?></th>
                                    <th><?php _e('blocks'); ?></th>
                                    <th><?php _e('percentage'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $totalFormBlocks = array_sum(array_column($stats['form_breakdown'], 'total'));
                                foreach ($stats['form_breakdown'] as $form): 
                                    $percentage = $totalFormBlocks > 0 ? ($form['total'] / $totalFormBlocks) * 100 : 0;
                                ?>
                                    <tr>
                                        <td>
                                            <span class="form-type-badge">
                                                <?php echo ucfirst($form['form_type']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo number_format($form['total']); ?></td>
                                        <td>
                                            <div class="progress-bar-container">
                                                <div class="progress-bar" style="width: <?php echo $percentage; ?>%"></div>
                                                <span class="progress-text"><?php echo number_format($percentage, 1); ?>%</span>
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
    </div>
</div>

<style>
.stats-summary {
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
    height: 100%;
    margin-top: 1rem;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.stat-icon.stat-primary {
    background: var(--primary-color);
}

.stat-icon.stat-danger {
    background: #dc3545;
}

.stat-icon.stat-success {
    background: #28a745;
}

.stat-icon.stat-warning {
    background: #ffc107;
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
    margin-bottom: 0.25rem;
}

.stat-period {
    color: #999;
    font-size: 0.8rem;
}

.card-title i {
    vertical-align: middle;
    margin-right: 0.5rem;
}

.empty-chart,
.empty-table {
    text-align: center;
    padding: 3rem 1rem;
    color: #666;
}

.empty-chart i,
.empty-table i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.top-ips-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.top-ip-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    border: 1px solid #e9ecef;
    border-radius: 6px;
    background: #f8f9fa;
}

.ip-rank {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 0.9rem;
}

.ip-info {
    flex: 1;
}

.ip-address {
    font-family: monospace;
    font-weight: 500;
    color: #333;
}

.ip-attempts {
    font-size: 0.8rem;
    color: #666;
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

.progress-bar-container {
    position: relative;
    width: 100%;
    height: 20px;
    background-color: #e9ecef;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, var(--primary-color), #007bff);
    transition: width 0.3s ease;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 0.75rem;
    font-weight: 500;
    color: #333;
}

.dropdown-menu {
    min-width: 150px;
}

.btn-group .btn {
    border-radius: 0.375rem;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chart data
const chartData = <?php echo json_encode($chartData); ?>;
const stats = <?php echo json_encode($stats); ?>;

// Daily Activity Chart
const dailyActivityCtx = document.getElementById('dailyActivityChart').getContext('2d');
new Chart(dailyActivityCtx, {
    type: 'line',
    data: {
        labels: chartData.daily?.labels || [],
        datasets: [
            {
                label: '<?php _e("form_attempts"); ?>',
                data: chartData.daily?.form_attempts || [],
                borderColor: '#007bff',
                backgroundColor: 'rgba(0, 123, 255, 0.1)',
                fill: true,
                tension: 0.4
            },
            {
                label: '<?php _e("bot_attempts"); ?>',
                data: chartData.daily?.bot_attempts || [],
                borderColor: '#dc3545',
                backgroundColor: 'rgba(220, 53, 69, 0.1)',
                fill: true,
                tension: 0.4
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Protection Methods Chart
if (stats.protection_breakdown && stats.protection_breakdown.length > 0) {
    const protectionCtx = document.getElementById('protectionMethodsChart').getContext('2d');
    new Chart(protectionCtx, {
        type: 'doughnut',
        data: {
            labels: stats.protection_breakdown.map(item => item.protection_type),
            datasets: [{
                data: stats.protection_breakdown.map(item => item.total),
                backgroundColor: [
                    '#1976d2',
                    '#7b1fa2',
                    '#388e3c',
                    '#f57c00',
                    '#d32f2f'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Form Types Chart
if (stats.form_breakdown && stats.form_breakdown.length > 0) {
    const formTypesCtx = document.getElementById('formTypesChart').getContext('2d');
    new Chart(formTypesCtx, {
        type: 'bar',
        data: {
            labels: stats.form_breakdown.map(item => item.form_type),
            datasets: [{
                label: '<?php _e("blocked_attempts"); ?>',
                data: stats.form_breakdown.map(item => item.total),
                backgroundColor: 'rgba(220, 53, 69, 0.8)',
                borderColor: '#dc3545',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

function blockIP(ipAddress) {
    if (confirm(`<?php _e("block_ip_confirm"); ?> ${ipAddress}?`)) {
        // Redirect to block IP form with pre-filled IP
        window.location.href = `<?php echo $adminUrl; ?>/antibot/blocks?add_ip=${encodeURIComponent(ipAddress)}`;
    }
}

// Auto-refresh every 5 minutes
setInterval(function() {
    if (document.visibilityState === 'visible') {
        location.reload();
    }
}, 300000);
</script>