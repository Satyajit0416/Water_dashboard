<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">My Dashboard</h1>
        <p class="page-subtitle">Welcome back, <?= e($farmer['name']) ?> &mdash; <?= e($farmer['farm_name']) ?></p>
    </div>
    <a href="<?= APP_URL ?>/water/add" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Log Water Usage
    </a>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-blue">
            <div class="stat-icon"><i class="fas fa-tint"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= formatLiters($stats['today_usage']) ?></div>
                <div class="stat-label">Today's Usage</div>
                <div class="stat-trend"><i class="fas fa-calendar-day"></i> <?= date('d M Y') ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-green">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= formatLiters($stats['month_usage']) ?></div>
                <div class="stat-label">This Month</div>
                <div class="stat-trend"><i class="fas fa-calendar-alt"></i> <?= date('F Y') ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-orange">
            <div class="stat-icon"><i class="fas fa-seedling"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['active_crops'] ?></div>
                <div class="stat-label">Active Crops</div>
                <div class="stat-trend"><i class="fas fa-leaf"></i> Currently Growing</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-purple">
            <div class="stat-icon"><i class="fas fa-clock"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['pending_schedules'] ?></div>
                <div class="stat-label">Pending Schedules</div>
                <div class="stat-trend"><i class="fas fa-calendar-check"></i> Upcoming</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Daily Water Usage (Last 7 Days)</h3>
                <div class="chart-legend">
                    <span class="legend-dot bg-info"></span> Liters
                </div>
            </div>
            <div class="chart-container">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Irrigation Methods</h3>
            </div>
            <div class="chart-container chart-sm">
                <canvas id="methodChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Monthly Trend + Crop Usage -->
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Monthly Trend</h3>
            </div>
            <div class="chart-container">
                <canvas id="monthlyChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Crop-wise Usage</h3>
            </div>
            <div class="chart-container">
                <canvas id="cropChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Suggestions + Schedules + Recent -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <h3 class="card-title-custom"><i class="fas fa-lightbulb text-warning me-2"></i>Water Saving Tips</h3>
            </div>
            <div class="card-body-custom">
                <?php foreach ($suggestions as $tip): ?>
                <div class="tip-item"><?= $tip ?></div>
                <?php endforeach; ?>

                <!-- Farm Info -->
                <div class="farm-info-mini mt-3">
                    <div class="finfo-row"><span>Farm Size</span><strong><?= $farmer['farm_size'] ?> acres</strong></div>
                    <div class="finfo-row"><span>Soil Type</span><strong><?= ucfirst($farmer['soil_type']) ?></strong></div>
                    <div class="finfo-row"><span>Water Source</span><strong><?= ucfirst($farmer['water_source']) ?></strong></div>
                    <div class="finfo-row"><span>Location</span><strong><?= e($farmer['location']) ?></strong></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Upcoming Schedules</h3>
                <a href="<?= APP_URL ?>/irrigation" class="view-all-link">View all</a>
            </div>
            <div class="card-body-custom">
                <?php if (empty($schedules)): ?>
                    <div class="empty-state-sm">
                        <i class="fas fa-calendar-plus"></i>
                        <p>No upcoming schedules</p>
                        <a href="<?= APP_URL ?>/irrigation" class="btn-sm-custom">Add Schedule</a>
                    </div>
                <?php else: ?>
                    <?php foreach (array_slice($schedules, 0, 4) as $s): ?>
                    <div class="schedule-item">
                        <div class="schedule-icon"><?= irrigationIcon($s['irrigation_method']) ?></div>
                        <div class="schedule-details">
                            <div class="schedule-crop"><?= e($s['crop_name'] ?? 'General') ?></div>
                            <div class="schedule-time">
                                <?= formatDate($s['scheduled_date']) ?> at <?= substr($s['scheduled_time'], 0, 5) ?>
                            </div>
                        </div>
                        <div class="schedule-water"><?= formatLiters($s['estimated_water']) ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Recent Records</h3>
                <a href="<?= APP_URL ?>/water" class="view-all-link">View all</a>
            </div>
            <div class="card-body-custom">
                <?php if (empty($recentUsage)): ?>
                    <div class="empty-state-sm">
                        <i class="fas fa-water"></i>
                        <p>No usage records yet</p>
                        <a href="<?= APP_URL ?>/water/add" class="btn-sm-custom">Log Now</a>
                    </div>
                <?php else: ?>
                    <?php foreach ($recentUsage as $r): ?>
                    <div class="recent-item">
                        <div class="recent-date"><?= formatDate($r['usage_date'], 'd M') ?></div>
                        <div class="recent-details">
                            <div class="recent-crop"><?= e($r['crop_name'] ?? 'General') ?></div>
                            <div class="recent-method"><?= ucfirst($r['irrigation_method']) ?></div>
                        </div>
                        <div class="recent-amount"><?= formatLiters($r['amount_used']) ?></div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// ---- Chart Data from PHP ----
const dailyLabels  = <?= json_encode(array_column($dailyUsage, 'usage_date')) ?>;
const dailyValues  = <?= json_encode(array_map('floatval', array_column($dailyUsage, 'total'))) ?>;
const monthLabels  = <?= json_encode(array_column($monthlyUsage, 'month_label')) ?>;
const monthValues  = <?= json_encode(array_map('floatval', array_column($monthlyUsage, 'total'))) ?>;
const cropLabels   = <?= json_encode(array_column($cropWiseUsage, 'crop_name')) ?>;
const cropValues   = <?= json_encode(array_map('floatval', array_column($cropWiseUsage, 'total'))) ?>;
const methodLabels = <?= json_encode(array_column($methodUsage, 'irrigation_method')) ?>;
const methodValues = <?= json_encode(array_map('floatval', array_column($methodUsage, 'total'))) ?>;

document.addEventListener('DOMContentLoaded', function() {
    const gridColor = 'rgba(255,255,255,0.06)';
    const textColor = '#94a3b8';

    // Daily Usage Bar Chart
    new Chart(document.getElementById('dailyChart'), {
        type: 'bar',
        data: {
            labels: dailyLabels.map(d => new Date(d).toLocaleDateString('en-IN', {day:'numeric', month:'short'})),
            datasets: [{
                label: 'Water Used (L)',
                data: dailyValues,
                backgroundColor: 'rgba(56, 189, 248, 0.7)',
                borderColor: '#38bdf8',
                borderWidth: 1,
                borderRadius: 6,
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

    // Monthly Line Chart
    new Chart(document.getElementById('monthlyChart'), {
        type: 'line',
        data: {
            labels: monthLabels,
            datasets: [{
                label: 'Monthly Usage (L)',
                data: monthValues,
                borderColor: '#4ade80',
                backgroundColor: 'rgba(74, 222, 128, 0.1)',
                fill: true, tension: 0.4, pointRadius: 5,
                pointBackgroundColor: '#4ade80',
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

    // Crop Doughnut
    const cropColors = ['#38bdf8','#4ade80','#f59e0b','#f87171','#a78bfa','#34d399'];
    new Chart(document.getElementById('cropChart'), {
        type: 'doughnut',
        data: {
            labels: cropLabels.length ? cropLabels : ['No Data'],
            datasets: [{ data: cropValues.length ? cropValues : [1], backgroundColor: cropColors, borderWidth: 0 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: textColor, padding: 12 } } }
        }
    });

    // Method Pie
    const methodColors = ['#38bdf8','#4ade80','#f59e0b','#f87171','#a78bfa'];
    new Chart(document.getElementById('methodChart'), {
        type: 'pie',
        data: {
            labels: methodLabels.map(m => m.charAt(0).toUpperCase() + m.slice(1)),
            datasets: [{ data: methodValues, backgroundColor: methodColors, borderWidth: 0 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { color: textColor, padding: 8, font: { size: 11 } } } }
        }
    });
});
</script>
