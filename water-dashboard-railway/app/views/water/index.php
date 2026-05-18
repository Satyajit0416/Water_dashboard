<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Water Usage Records</h1>
        <p class="page-subtitle">Track and manage your irrigation logs</p>
    </div>
    <?php if (empty($isAdmin)): ?>
    <a href="<?= APP_URL ?>/water/add" class="btn-primary-custom">
        <i class="fas fa-plus me-2"></i>Log Usage
    </a>
    <?php endif; ?>
</div>

<!-- Stats Mini -->
<?php if (!empty($stats)): ?>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="mini-stat-card">
            <i class="fas fa-calendar-day text-info"></i>
            <div class="ms-3">
                <div class="mini-stat-val"><?= formatLiters($stats['today']) ?></div>
                <div class="mini-stat-label">Today</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="mini-stat-card">
            <i class="fas fa-calendar-alt text-success"></i>
            <div class="ms-3">
                <div class="mini-stat-val"><?= formatLiters($stats['month']) ?></div>
                <div class="mini-stat-label">This Month</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="mini-stat-card">
            <i class="fas fa-list-ol text-warning"></i>
            <div class="ms-3">
                <div class="mini-stat-val"><?= count($records) ?></div>
                <div class="mini-stat-label">Total Records</div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="card-custom mb-4">
    <div class="card-body-custom">
        <form method="GET" class="filter-form">
            <div class="row g-3 align-items-end">
                <div class="col-sm-6 col-md-3">
                    <label class="filter-label">From Date</label>
                    <input type="date" name="date_from" class="form-control-custom-sm" value="<?= e($filters['date_from'] ?? '') ?>">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="filter-label">To Date</label>
                    <input type="date" name="date_to" class="form-control-custom-sm" value="<?= e($filters['date_to'] ?? '') ?>">
                </div>
                <div class="col-sm-6 col-md-3">
                    <label class="filter-label">Method</label>
                    <select name="method" class="form-control-custom-sm">
                        <option value="">All Methods</option>
                        <?php foreach (['drip','sprinkler','flood','furrow','subsurface'] as $m): ?>
                        <option value="<?= $m ?>" <?= ($filters['method'] ?? '') === $m ? 'selected' : '' ?>><?= ucfirst($m) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-sm-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn-primary-custom flex-grow-1">
                        <i class="fas fa-search me-1"></i>Filter
                    </button>
                    <a href="<?= APP_URL ?>/water" class="btn-outline-custom">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Records Table -->
<div class="card-custom">
    <div class="table-responsive">
        <?php if (empty($records)): ?>
            <div class="empty-state p-5 text-center">
                <i class="fas fa-water fa-3x text-muted mb-3"></i>
                <h5>No records found</h5>
                <p class="text-muted">Start by logging your water usage</p>
                <a href="<?= APP_URL ?>/water/add" class="btn-primary-custom">Log Usage</a>
            </div>
        <?php else: ?>
        <table class="table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <?php if (!empty($isAdmin)): ?><th>Farmer</th><?php endif; ?>
                    <th>Crop</th>
                    <th>Method</th>
                    <th>Duration</th>
                    <th>Area</th>
                    <th>Amount Used</th>
                    <th>Notes</th>
                    <?php if (empty($isAdmin)): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $i => $r): ?>
                <tr>
                    <td class="text-muted"><?= $i + 1 ?></td>
                    <td><?= formatDate($r['usage_date']) ?></td>
                    <?php if (!empty($isAdmin)): ?><td><?= e($r['farmer_name']) ?></td><?php endif; ?>
                    <td><?= e($r['crop_name'] ?? '—') ?></td>
                    <td>
                        <span class="method-badge method-<?= $r['irrigation_method'] ?>">
                            <?= irrigationIcon($r['irrigation_method']) ?> <?= ucfirst($r['irrigation_method']) ?>
                        </span>
                    </td>
                    <td><?= $r['duration_minutes'] ?> min</td>
                    <td><?= $r['area_irrigated'] ? $r['area_irrigated'] . ' ac' : '—' ?></td>
                    <td><span class="amount-badge"><?= formatLiters($r['amount_used']) ?></span></td>
                    <td class="text-muted"><?= $r['notes'] ? substr(e($r['notes']), 0, 40) . '...' : '—' ?></td>
                    <?php if (empty($isAdmin)): ?>
                    <td>
                        <a href="<?= APP_URL ?>/water/edit/<?= $r['id'] ?>" class="action-icon" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= APP_URL ?>/water/delete/<?= $r['id'] ?>" 
                           class="action-icon text-danger" title="Delete"
                           onclick="return confirm('Delete this record?')">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                    <?php endif; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
