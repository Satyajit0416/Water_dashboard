<div class="page-header">
    <div>
        <h1 class="page-title">My Crops</h1>
        <p class="page-subtitle">Manage crops on <?= e($farmer['farm_name']) ?></p>
    </div>
    <a href="<?= APP_URL ?>/crop/add" class="btn-primary-custom"><i class="fas fa-plus me-2"></i>Add Crop</a>
</div>

<?php if (empty($crops)): ?>
<div class="empty-state p-5 text-center card-custom">
    <i class="fas fa-seedling fa-3x text-muted mb-3"></i>
    <h5>No crops added yet</h5>
    <p class="text-muted">Start by adding your first crop</p>
    <a href="<?= APP_URL ?>/crop/add" class="btn-primary-custom">Add Crop</a>
</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($crops as $c): ?>
    <div class="col-sm-6 col-xl-4">
        <div class="crop-card">
            <div class="crop-card-header">
                <div class="crop-type-icon">
                    <?php
                    $icons = ['cereal'=>'🌾','vegetable'=>'🥦','fruit'=>'🍎','pulse'=>'🫘','oilseed'=>'🌻','cash_crop'=>'💰'];
                    echo $icons[$c['crop_type']] ?? '🌱';
                    ?>
                </div>
                <div class="crop-status"><?= statusBadge($c['status']) ?></div>
            </div>
            <div class="crop-card-body">
                <h4 class="crop-name"><?= e($c['crop_name']) ?></h4>
                <p class="crop-type"><?= ucfirst(str_replace('_', ' ', $c['crop_type'])) ?></p>
                <div class="crop-meta">
                    <div class="crop-meta-item"><i class="fas fa-ruler-combined"></i><?= $c['area_planted'] ?> acres</div>
                    <div class="crop-meta-item"><i class="fas fa-tint"></i><?= formatLiters($c['total_water_used']) ?></div>
                    <div class="crop-meta-item"><i class="fas fa-calendar"></i><?= formatDate($c['planting_date']) ?></div>
                    <div class="crop-meta-item"><i class="fas fa-leaf"></i><?= ucfirst($c['growth_stage']) ?></div>
                </div>
                <div class="crop-harvest">
                    <span>Harvest:</span> <?= formatDate($c['expected_harvest']) ?>
                </div>
            </div>
            <div class="crop-card-footer">
                <a href="<?= APP_URL ?>/crop/edit/<?= $c['id'] ?>" class="btn-sm-custom"><i class="fas fa-edit me-1"></i>Edit</a>
                <a href="<?= APP_URL ?>/crop/delete/<?= $c['id'] ?>" class="btn-sm-danger ms-2" onclick="return confirm('Delete this crop?')"><i class="fas fa-trash me-1"></i>Delete</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
