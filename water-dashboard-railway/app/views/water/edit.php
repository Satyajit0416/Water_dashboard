<div class="page-header">
    <div>
        <h1 class="page-title">Edit Water Usage</h1>
        <p class="page-subtitle">Update irrigation record #<?= $record['id'] ?></p>
    </div>
    <a href="<?= APP_URL ?>/water" class="btn-outline-custom"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom"><i class="fas fa-edit me-2 text-info"></i>Edit Record</h3>
            </div>
            <div class="card-body-custom">
                <form action="<?= APP_URL ?>/water/update/<?= $record['id'] ?>" method="POST" class="form-custom">
                    <?= csrfField() ?>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Date *</label>
                            <input type="date" name="usage_date" class="form-control-custom" value="<?= $record['usage_date'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Crop</label>
                            <select name="crop_id" class="form-control-custom">
                                <option value="">-- No specific crop --</option>
                                <?php foreach ($crops as $c): ?>
                                <option value="<?= $c['id'] ?>" <?= $record['crop_id'] == $c['id'] ? 'selected' : '' ?>>
                                    <?= e($c['crop_name']) ?> (<?= $c['area_planted'] ?> acres)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Irrigation Method *</label>
                            <select name="irrigation_method" class="form-control-custom" required>
                                <?php foreach (['drip','sprinkler','flood','furrow','subsurface'] as $m): ?>
                                <option value="<?= $m ?>" <?= $record['irrigation_method'] === $m ? 'selected' : '' ?>><?= ucfirst($m) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Duration (minutes) *</label>
                            <input type="number" name="duration_minutes" class="form-control-custom" value="<?= $record['duration_minutes'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Amount Used (liters) *</label>
                            <input type="number" name="amount_used" class="form-control-custom" value="<?= $record['amount_used'] ?>" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Area Irrigated (acres)</label>
                            <input type="number" name="area_irrigated" class="form-control-custom" value="<?= $record['area_irrigated'] ?>" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Pump Power (HP)</label>
                            <input type="number" name="pump_power" class="form-control-custom" value="<?= $record['pump_power'] ?>" step="0.01">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Notes</label>
                            <input type="text" name="notes" class="form-control-custom" value="<?= e($record['notes']) ?>">
                        </div>
                    </div>
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn-primary-custom"><i class="fas fa-save me-2"></i>Update Record</button>
                        <a href="<?= APP_URL ?>/water" class="btn-outline-custom ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
