<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Admin Dashboard</h1>
        <p class="page-subtitle">System overview &mdash; All farmers & water analytics</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= APP_URL ?>/admin/reports" class="btn-outline-custom">
            <i class="fas fa-download me-2"></i>Export Report
        </a>
        <a href="<?= APP_URL ?>/admin/farmers" class="btn-primary-custom">
            <i class="fas fa-users me-2"></i>Manage Farmers
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-card-blue">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total_farmers'] ?></div>
                <div class="stat-label">Total Farmers</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-card-green">
            <div class="stat-icon"><i class="fas fa-tint"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= formatLiters($stats['today_usage']) ?></div>
                <div class="stat-label">Today's Usage</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-card-orange">
            <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= formatLiters($stats['month_usage']) ?></div>
                <div class="stat-label">This Month</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-card-red">
            <div class="stat-icon"><i class="fas fa-database"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= formatLiters($stats['total_water_usage']) ?></div>
                <div class="stat-label">Total Usage</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-card-purple">
            <div class="stat-icon"><i class="fas fa-seedling"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['active_crops'] ?></div>
                <div class="stat-label">Active Crops</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="stat-card stat-card-teal">
            <div class="stat-icon"><i class="fas fa-user-check"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total_users'] ?></div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Monthly Water Consumption Trend</h3>
            </div>
            <div class="chart-container">
                <canvas id="adminMonthlyChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Irrigation Methods</h3>
            </div>
            <div class="chart-container">
                <canvas id="adminMethodChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Crop-wise Water Usage</h3>
            </div>
            <div class="chart-container">
                <canvas id="adminCropChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Upcoming Irrigation</h3>
            </div>
            <div class="card-body-custom">
                <?php if (empty($upcomingIrrigation)): ?>
                    <div class="empty-state-sm"><i class="fas fa-calendar"></i><p>No upcoming schedules</p></div>
                <?php else: ?>
                    <?php foreach ($upcomingIrrigation as $s): ?>
                    <div class="schedule-item">
                        <div class="schedule-icon"><?= irrigationIcon($s['irrigation_method']) ?></div>
                        <div class="schedule-details">
                            <div class="schedule-crop"><?= e($s['farmer_name']) ?> &mdash; <?= e($s['crop_name'] ?? 'General') ?></div>
                            <div class="schedule-time"><?= formatDate($s['scheduled_date']) ?> at <?= substr($s['scheduled_time'], 0, 5) ?></div>
                        </div>
                        <div class="schedule-water"><?= formatLiters($s['estimated_water']) ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Farmers Table -->
<div class="card-custom mb-4">
    <div class="card-header-custom">
        <h3 class="card-title-custom">Farmers Overview</h3>
        <a href="<?= APP_URL ?>/admin/farmers" class="view-all-link">Manage All</a>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Farmer</th>
                    <th>Farm</th>
                    <th>Location</th>
                    <th>Crops</th>
                    <th>Total Usage</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($farmers, 0, 5) as $f): ?>
                <tr>
                    <td>
                        <div class="farmer-cell">
                            <div class="farmer-avatar"><?= strtoupper(substr($f['name'], 0, 1)) ?></div>
                            <div>
                                <div class="farmer-name"><?= e($f['name']) ?></div>
                                <div class="farmer-email"><?= e($f['email']) ?></div>
                            </div>
                        </div>
                    </td>
                    <td><?= e($f['farm_name']) ?></td>
                    <td><i class="fas fa-map-marker-alt text-muted me-1"></i><?= e($f['location']) ?></td>
                    <td><span class="badge-custom"><?= $f['crop_count'] ?> crops</span></td>
                    <td><?= formatLiters($f['total_usage']) ?></td>
                    <td><?= $f['is_active'] ? '<span class="status-active">Active</span>' : '<span class="status-inactive">Inactive</span>' ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/admin/viewFarmer/<?= $f['id'] ?>" class="action-icon" title="View">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Recent Water Usage -->
<div class="card-custom">
    <div class="card-header-custom">
        <h3 class="card-title-custom">Recent Water Usage Records</h3>
        <a href="<?= APP_URL ?>/admin/waterUsage" class="view-all-link">View All</a>
    </div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead>
                <tr><th>Date</th><th>Farmer</th><th>Crop</th><th>Method</th><th>Duration</th><th>Amount</th></tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($recentUsage, 0, 8) as $r): ?>
                <tr>
                    <td><?= formatDate($r['usage_date']) ?></td>
                    <td><?= e($r['farmer_name']) ?></td>
                    <td><?= e($r['crop_name'] ?? '—') ?></td>
                    <td><?= ucfirst($r['irrigation_method']) ?></td>
                    <td><?= $r['duration_minutes'] ?> min</td>
                    <td><span class="amount-badge"><?= formatLiters($r['amount_used']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const adminMonthLabels  = <?= json_encode(array_column($monthlyUsage, 'month_label')) ?>;
const adminMonthValues  = <?= json_encode(array_map('floatval', array_column($monthlyUsage, 'total'))) ?>;
const adminCropLabels   = <?= json_encode(array_column($cropWiseUsage, 'crop_name')) ?>;
const adminCropValues   = <?= json_encode(array_map('floatval', array_column($cropWiseUsage, 'total'))) ?>;
const adminMethodLabels = <?= json_encode(array_column($methodUsage, 'irrigation_method')) ?>;
const adminMethodValues = <?= json_encode(array_map('floatval', array_column($methodUsage, 'total'))) ?>;

document.addEventListener('DOMContentLoaded', function() {
    const gridColor = 'rgba(255,255,255,0.06)';
    const textColor = '#94a3b8';
    const colors = ['#38bdf8','#4ade80','#f59e0b','#f87171','#a78bfa','#34d399'];

    new Chart(document.getElementById('adminMonthlyChart'), {
        type: 'line',
        data: {
            labels: adminMonthLabels,
            datasets: [{
                label: 'Total Water Used (L)',
                data: adminMonthValues,
                borderColor: '#4ade80',
                backgroundColor: 'rgba(74,222,128,0.15)',
                fill: true, tension: 0.4, pointRadius: 6,
                pointBackgroundColor: '#4ade80'
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: textColor } },
                y: { grid: { color: gridColor }, ticks: { color: textColor } }
            }
        }
    });

    new Chart(document.getElementById('adminCropChart'), {
        type: 'bar',
        data: {
            labels: adminCropLabels.length ? adminCropLabels : ['No data'],
            datasets: [{
                data: adminCropValues.length ? adminCropValues : [0],
                backgroundColor: colors,
                borderRadius: 6, borderWidth: 0
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: gridColor }, ticks: { color: textColor } },
                y: { grid: { color: gridColor }, ticks: { color: textColor } }
            }
        }
    });

    new Chart(document.getElementById('adminMethodChart'), {
        type: 'doughnut',
        data: {
            labels: adminMethodLabels.map(m => m.charAt(0).toUpperCase() + m.slice(1)),
            datasets: [{ data: adminMethodValues, backgroundColor: colors, borderWidth: 0 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: textColor, padding: 10 } } }
        }
    });
});
</script>
