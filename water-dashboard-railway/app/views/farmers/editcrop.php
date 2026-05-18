<div class="page-header">
    <div><h1 class="page-title">Edit Crop</h1><p class="page-subtitle">Update crop details</p></div>
    <a href="<?= APP_URL ?>/crop" class="btn-outline-custom"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="row"><div class="col-lg-8">
    <div class="card-custom">
        <div class="card-header-custom"><h3 class="card-title-custom">Edit: <?= e($crop['crop_name']) ?></h3></div>
        <div class="card-body-custom">
            <form action="<?= APP_URL ?>/crop/update/<?= $crop['id'] ?>" method="POST" class="form-custom">
                <?= csrfField() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Crop Name *</label>
                        <input type="text" name="crop_name" class="form-control-custom" value="<?= e($crop['crop_name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Crop Type *</label>
                        <select name="crop_type" class="form-control-custom" required>
                            <?php foreach (['cereal','vegetable','fruit','pulse','oilseed','cash_crop'] as $t): ?>
                            <option value="<?= $t ?>" <?= $crop['crop_type'] === $t ? 'selected' : '' ?>><?= ucfirst(str_replace('_',' ',$t)) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Area Planted (acres) *</label>
                        <input type="number" name="area_planted" class="form-control-custom" value="<?= $crop['area_planted'] ?>" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Water Requirement (L/acre/day)</label>
                        <input type="number" name="water_requirement" class="form-control-custom" value="<?= $crop['water_requirement'] ?>" step="0.01">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Planting Date *</label>
                        <input type="date" name="planting_date" class="form-control-custom" value="<?= $crop['planting_date'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Expected Harvest *</label>
                        <input type="date" name="expected_harvest" class="form-control-custom" value="<?= $crop['expected_harvest'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Growth Stage</label>
                        <select name="growth_stage" class="form-control-custom">
                            <?php foreach (['seedling','vegetative','flowering','fruiting','harvest'] as $stage): ?>
                            <option value="<?= $stage ?>" <?= $crop['growth_stage'] === $stage ? 'selected' : '' ?>><?= ucfirst($stage) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Status</label>
                        <select name="status" class="form-control-custom">
                            <?php foreach (['active','harvested','failed'] as $s): ?>
                            <option value="<?= $s ?>" <?= $crop['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-actions mt-4">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-save me-2"></i>Update Crop</button>
                    <a href="<?= APP_URL ?>/crop" class="btn-outline-custom ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
