<div class="page-header">
    <div>
        <h1 class="page-title"><?= e($farmer['farm_name']) ?></h1>
        <p class="page-subtitle">Farmer: <?= e($farmer['name']) ?> &mdash; <?= e($farmer['location']) ?></p>
    </div>
    <a href="<?= APP_URL ?>/admin/farmers" class="btn-outline-custom"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-blue">
            <div class="stat-icon"><i class="fas fa-list"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['total_records'] ?></div>
                <div class="stat-label">Total Records</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-green">
            <div class="stat-icon"><i class="fas fa-tint"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= formatLiters($stats['total_usage']) ?></div>
                <div class="stat-label">Total Usage</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-orange">
            <div class="stat-icon"><i class="fas fa-seedling"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= $stats['crop_count'] ?></div>
                <div class="stat-label">Active Crops</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-purple">
            <div class="stat-icon"><i class="fas fa-chart-bar"></i></div>
            <div class="stat-content">
                <div class="stat-value"><?= formatLiters($stats['avg_usage']) ?></div>
                <div class="stat-label">Avg per Record</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">Daily Usage (30 Days)</h3></div>
            <div class="chart-container"><canvas id="farmerChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-custom h-100">
            <div class="card-header-custom"><h3 class="card-title-custom">Farm Details</h3></div>
            <div class="card-body-custom">
                <div class="farm-info-mini">
                    <div class="finfo-row"><span>Farm Size</span><strong><?= $farmer['farm_size'] ?> acres</strong></div>
                    <div class="finfo-row"><span>Soil Type</span><strong><?= ucfirst($farmer['soil_type'] ?? '—') ?></strong></div>
                    <div class="finfo-row"><span>Water Source</span><strong><?= ucfirst($farmer['water_source'] ?? '—') ?></strong></div>
                    <div class="finfo-row"><span>Phone</span><strong><?= e($farmer['phone'] ?? '—') ?></strong></div>
                    <div class="finfo-row"><span>Email</span><strong><?= e($farmer['email']) ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">Crops</h3></div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Crop</th><th>Area</th><th>Stage</th><th>Status</th><th>Water Used</th></tr></thead>
                    <tbody>
                        <?php foreach ($crops as $c): ?>
                        <tr>
                            <td><?= e($c['crop_name']) ?></td>
                            <td><?= $c['area_planted'] ?> ac</td>
                            <td><?= ucfirst($c['growth_stage']) ?></td>
                            <td><?= statusBadge($c['status']) ?></td>
                            <td><?= formatLiters($c['total_water_used']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">Recent Usage Records</h3></div>
            <div class="table-responsive">
                <table class="table-custom">
                    <thead><tr><th>Date</th><th>Crop</th><th>Method</th><th>Amount</th></tr></thead>
                    <tbody>
                        <?php foreach ($recentUsage as $r): ?>
                        <tr>
                            <td><?= formatDate($r['usage_date']) ?></td>
                            <td><?= e($r['crop_name'] ?? '—') ?></td>
                            <td><?= ucfirst($r['irrigation_method']) ?></td>
                            <td><?= formatLiters($r['amount_used']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
const fl = <?= json_encode(array_column($dailyUsage, 'usage_date')) ?>;
const fv = <?= json_encode(array_map('floatval', array_column($dailyUsage, 'total'))) ?>;
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('farmerChart'), {
        type: 'bar',
        data: {
            labels: fl.map(d => new Date(d).toLocaleDateString('en-IN', {day:'numeric',month:'short'})),
            datasets: [{ label: 'Liters', data: fv, backgroundColor: 'rgba(56,189,248,0.7)', borderRadius: 6 }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#94a3b8' } },
                y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#94a3b8' } }
            }
        }
    });
});
</script>
