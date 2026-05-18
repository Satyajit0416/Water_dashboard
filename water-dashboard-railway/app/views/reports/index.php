<div class="page-header">
    <div><h1 class="page-title">Reports & Analytics</h1><p class="page-subtitle">Comprehensive water usage analysis</p></div>
    <button onclick="window.print()" class="btn-outline-custom"><i class="fas fa-print me-2"></i>Print Report</button>
</div>

<!-- Summary Cards -->
<div class="row g-4 mb-4">
    <?php 
    $totalAll = array_sum(array_column($monthlyUsage, 'total'));
    $avgMonthly = count($monthlyUsage) > 0 ? $totalAll / count($monthlyUsage) : 0;
    ?>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-blue">
            <div class="stat-icon"><i class="fas fa-database"></i></div>
            <div class="stat-content"><div class="stat-value"><?= formatLiters($totalAll) ?></div><div class="stat-label">Total All Time</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-green">
            <div class="stat-icon"><i class="fas fa-chart-line"></i></div>
            <div class="stat-content"><div class="stat-value"><?= formatLiters($avgMonthly) ?></div><div class="stat-label">Monthly Average</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-orange">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-content"><div class="stat-value"><?= count($farmers) ?></div><div class="stat-label">Total Farmers</div></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card-purple">
            <div class="stat-icon"><i class="fas fa-seedling"></i></div>
            <div class="stat-content"><div class="stat-value"><?= count($cropUsage) ?></div><div class="stat-label">Crop Types</div></div>
        </div>
    </div>
</div>

<!-- Charts -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">12-Month Water Consumption</h3></div>
            <div class="chart-container"><canvas id="reportMonthlyChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">Method Distribution</h3></div>
            <div class="chart-container"><canvas id="reportMethodChart"></canvas></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">Crop-wise Water Usage</h3></div>
            <div class="chart-container"><canvas id="reportCropChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">Farmer Comparison</h3></div>
            <div class="chart-container"><canvas id="farmerCompChart"></canvas></div>
        </div>
    </div>
</div>

<!-- Farmer Table -->
<div class="card-custom">
    <div class="card-header-custom"><h3 class="card-title-custom">Farmer-wise Report</h3></div>
    <div class="table-responsive">
        <table class="table-custom">
            <thead><tr><th>Farmer</th><th>Farm</th><th>Location</th><th>Crops</th><th>Records</th><th>Total Usage</th></tr></thead>
            <tbody>
                <?php foreach ($farmers as $f): ?>
                <tr>
                    <td><?= e($f['name']) ?></td>
                    <td><?= e($f['farm_name']) ?></td>
                    <td><?= e($f['location']) ?></td>
                    <td><?= $f['crop_count'] ?></td>
                    <td><?= $f['total_records'] ?></td>
                    <td><span class="amount-badge"><?= formatLiters($f['total_usage']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const rml = <?= json_encode(array_column($monthlyUsage, 'month_label')) ?>;
const rmv = <?= json_encode(array_map('floatval', array_column($monthlyUsage, 'total'))) ?>;
const rcl = <?= json_encode(array_column($cropUsage, 'crop_name')) ?>;
const rcv = <?= json_encode(array_map('floatval', array_column($cropUsage, 'total'))) ?>;
const rml2 = <?= json_encode(array_column($methodUsage, 'irrigation_method')) ?>;
const rmv2 = <?= json_encode(array_map('floatval', array_column($methodUsage, 'total'))) ?>;
const fnames = <?= json_encode(array_column($farmers, 'name')) ?>;
const fusage = <?= json_encode(array_map(function($f){ return floatval($f['total_usage']); }, $farmers)) ?>;

document.addEventListener('DOMContentLoaded', function() {
    const gc = 'rgba(255,255,255,0.06)', tc = '#94a3b8';
    const colors = ['#38bdf8','#4ade80','#f59e0b','#f87171','#a78bfa','#34d399','#fb923c'];

    new Chart(document.getElementById('reportMonthlyChart'), {
        type: 'bar', data: { labels: rml, datasets: [{ data: rmv, backgroundColor: '#4ade80', borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { color: gc }, ticks: { color: tc } }, y: { grid: { color: gc }, ticks: { color: tc } } } }
    });

    new Chart(document.getElementById('reportCropChart'), {
        type: 'bar', data: { labels: rcl.length ? rcl : ['No data'], datasets: [{ data: rcv.length ? rcv : [0], backgroundColor: colors, borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { color: gc }, ticks: { color: tc } }, y: { grid: { color: gc }, ticks: { color: tc } } } }
    });

    new Chart(document.getElementById('reportMethodChart'), {
        type: 'doughnut', data: { labels: rml2.map(m => m.charAt(0).toUpperCase()+m.slice(1)), datasets: [{ data: rmv2, backgroundColor: colors, borderWidth: 0 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { color: tc, padding: 10 } } } }
    });

    new Chart(document.getElementById('farmerCompChart'), {
        type: 'bar', data: { labels: fnames, datasets: [{ label: 'Water Used (L)', data: fusage, backgroundColor: '#38bdf8', borderRadius: 6 }] },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { color: gc }, ticks: { color: tc } }, y: { grid: { color: gc }, ticks: { color: tc } } } }
    });
});
</script>
