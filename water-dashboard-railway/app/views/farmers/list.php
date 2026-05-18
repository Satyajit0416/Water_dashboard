<div class="page-header">
    <div>
        <h1 class="page-title">Manage Farmers</h1>
        <p class="page-subtitle"><?= count($farmers) ?> registered farmers in the system</p>
    </div>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <?php if (empty($farmers)): ?>
        <div class="empty-state p-5 text-center">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h5>No farmers registered yet</h5>
        </div>
        <?php else: ?>
        <table class="table-custom">
            <thead>
                <tr><th>Farmer</th><th>Farm Name</th><th>Location</th><th>Farm Size</th><th>Crops</th><th>Total Usage</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($farmers as $f): ?>
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
                    <td><?= $f['farm_size'] ?> ac</td>
                    <td><span class="badge-custom"><?= $f['crop_count'] ?> crops</span></td>
                    <td><?= formatLiters($f['total_usage']) ?></td>
                    <td>
                        <?php if ($f['is_active']): ?>
                            <span class="status-active">Active</span>
                        <?php else: ?>
                            <span class="status-inactive">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= APP_URL ?>/admin/viewFarmer/<?= $f['id'] ?>" class="action-icon" title="View"><i class="fas fa-eye"></i></a>
                        <a href="<?= APP_URL ?>/admin/toggleFarmer/<?= $f['user_id'] ?>" class="action-icon text-warning" title="Toggle Status"><i class="fas fa-toggle-on"></i></a>
                        <a href="<?= APP_URL ?>/admin/deleteFarmer/<?= $f['user_id'] ?>" class="action-icon text-danger" title="Delete" onclick="return confirm('Delete this farmer and all data?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>
