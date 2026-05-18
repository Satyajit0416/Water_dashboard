<div class="page-header">
    <div><h1 class="page-title">My Profile</h1><p class="page-subtitle">Manage your account and farm details</p></div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card-custom text-center p-4">
            <div class="profile-avatar"><?= strtoupper(substr($farmer['name'], 0, 1)) ?></div>
            <h4 class="profile-name mt-3"><?= e($farmer['name']) ?></h4>
            <p class="profile-role">Farmer</p>
            <p class="profile-farm"><i class="fas fa-home me-1"></i><?= e($farmer['farm_name']) ?></p>
            <p class="profile-location"><i class="fas fa-map-marker-alt me-1"></i><?= e($farmer['location']) ?></p>
            <div class="profile-stats mt-3">
                <div class="pstat"><span><?= $farmer['farm_size'] ?></span><small>Acres</small></div>
                <div class="pstat"><span><?= ucfirst($farmer['soil_type'] ?? '—') ?></span><small>Soil</small></div>
                <div class="pstat"><span><?= ucfirst($farmer['water_source'] ?? '—') ?></span><small>Source</small></div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom"><h3 class="card-title-custom">Update Profile</h3></div>
            <div class="card-body-custom">
                <form action="<?= APP_URL ?>/dashboard/updateProfile" method="POST" class="form-custom">
                    <h6 class="section-label mb-3">Personal Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Full Name</label>
                            <input type="text" name="name" class="form-control-custom" value="<?= e($farmer['name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Email</label>
                            <input type="email" name="email" class="form-control-custom" value="<?= e($farmer['email']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Phone</label>
                            <input type="text" name="phone" class="form-control-custom" value="<?= e($farmer['phone'] ?? '') ?>">
                        </div>
                    </div>
                    <h6 class="section-label mt-4 mb-3">Farm Details</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Farm Name</label>
                            <input type="text" name="farm_name" class="form-control-custom" value="<?= e($farmer['farm_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Location</label>
                            <input type="text" name="location" class="form-control-custom" value="<?= e($farmer['location']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Farm Size (acres)</label>
                            <input type="number" name="farm_size" class="form-control-custom" value="<?= $farmer['farm_size'] ?>" step="0.01">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Soil Type</label>
                            <select name="soil_type" class="form-control-custom">
                                <?php foreach (['loamy','clay','sandy','silty','peaty','chalky'] as $s): ?>
                                <option value="<?= $s ?>" <?= ($farmer['soil_type'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Water Source</label>
                            <select name="water_source" class="form-control-custom">
                                <?php foreach (['borewell','canal','rainwater','river','tank'] as $s): ?>
                                <option value="<?= $s ?>" <?= ($farmer['water_source'] ?? '') === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <h6 class="section-label mt-4 mb-3">Change Password (optional)</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">New Password</label>
                            <input type="password" name="new_password" class="form-control-custom" placeholder="Leave blank to keep current">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control-custom" placeholder="Repeat new password">
                        </div>
                    </div>
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn-primary-custom"><i class="fas fa-save me-2"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
