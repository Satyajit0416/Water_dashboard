<div class="page-header">
    <div>
        <h1 class="page-title">Irrigation Schedule</h1>
        <p class="page-subtitle">Plan and manage your irrigation timetable</p>
    </div>
    <button class="btn-primary-custom" onclick="document.getElementById('addScheduleModal').style.display='flex'">
        <i class="fas fa-plus me-2"></i>Add Schedule
    </button>
</div>

<!-- Schedule Table -->
<div class="card-custom">
    <div class="table-responsive">
        <?php if (empty($schedules)): ?>
        <div class="empty-state p-5 text-center">
            <i class="fas fa-calendar-plus fa-3x text-muted mb-3"></i>
            <h5>No schedules yet</h5>
            <p class="text-muted">Plan your next irrigation session</p>
        </div>
        <?php else: ?>
        <table class="table-custom">
            <thead>
                <tr><th>Crop</th><th>Date</th><th>Time</th><th>Method</th><th>Duration</th><th>Est. Water</th><th>Status</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $s): ?>
                <tr>
                    <td><?= e($s['crop_name'] ?? 'General') ?></td>
                    <td><?= formatDate($s['scheduled_date']) ?></td>
                    <td><?= substr($s['scheduled_time'], 0, 5) ?></td>
                    <td><?= irrigationIcon($s['irrigation_method']) ?> <?= ucfirst($s['irrigation_method']) ?></td>
                    <td><?= $s['duration_minutes'] ?> min</td>
                    <td><span class="amount-badge"><?= formatLiters($s['estimated_water']) ?></span></td>
                    <td><?= statusBadge($s['status']) ?></td>
                    <td>
                        <?php if ($s['status'] === 'pending'): ?>
                        <form method="POST" action="<?= APP_URL ?>/irrigation/updateStatus/<?= $s['id'] ?>" style="display:inline">
                            <?= csrfField() ?>
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="action-icon text-success" title="Mark Complete"><i class="fas fa-check"></i></button>
                        </form>
                        <?php endif; ?>
                        <a href="<?= APP_URL ?>/irrigation/delete/<?= $s['id'] ?>" class="action-icon text-danger" onclick="return confirm('Delete this schedule?')" title="Delete"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- Add Schedule Modal -->
<div class="modal-overlay" id="addScheduleModal" style="display:none">
    <div class="modal-box">
        <div class="modal-header-custom">
            <h4>Add Irrigation Schedule</h4>
            <button onclick="document.getElementById('addScheduleModal').style.display='none'" class="modal-close">&times;</button>
        </div>
        <form method="POST" action="<?= APP_URL ?>/irrigation/store" class="form-custom">
            <?= csrfField() ?>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label-custom">Crop</label>
                    <select name="crop_id" class="form-control-custom">
                        <option value="">-- General --</option>
                        <?php foreach ($crops as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= e($c['crop_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Irrigation Method *</label>
                    <select name="irrigation_method" class="form-control-custom" required>
                        <option value="drip">💧 Drip</option>
                        <option value="sprinkler">🌧️ Sprinkler</option>
                        <option value="flood">🌊 Flood</option>
                        <option value="furrow">🌾 Furrow</option>
                        <option value="subsurface">⬇️ Subsurface</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Scheduled Date *</label>
                    <input type="date" name="scheduled_date" class="form-control-custom" min="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Scheduled Time *</label>
                    <input type="time" name="scheduled_time" class="form-control-custom" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Duration (minutes) *</label>
                    <input type="number" name="duration_minutes" class="form-control-custom" min="1" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label-custom">Estimated Water (liters) *</label>
                    <input type="number" name="estimated_water" class="form-control-custom" step="0.01" min="1" required>
                </div>
                <div class="col-12">
                    <label class="form-label-custom">Notes</label>
                    <input type="text" name="notes" class="form-control-custom" placeholder="Optional notes...">
                </div>
            </div>
            <div class="form-actions mt-3">
                <button type="submit" class="btn-primary-custom"><i class="fas fa-save me-2"></i>Save Schedule</button>
                <button type="button" class="btn-outline-custom ms-2" onclick="document.getElementById('addScheduleModal').style.display='none'">Cancel</button>
            </div>
        </form>
    </div>
</div>
