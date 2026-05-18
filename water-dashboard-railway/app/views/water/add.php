<!-- Page Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">Log Water Usage</h1>
        <p class="page-subtitle">Record your irrigation for <?= e($farmer['farm_name']) ?></p>
    </div>
    <a href="<?= APP_URL ?>/water" class="btn-outline-custom">
        <i class="fas fa-arrow-left me-2"></i>Back to Records
    </a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom"><i class="fas fa-tint me-2 text-info"></i>Usage Details</h3>
            </div>
            <div class="card-body-custom">
                <form action="<?= APP_URL ?>/water/store" method="POST" class="form-custom">
                    <?= csrfField() ?>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Date *</label>
                            <input type="date" name="usage_date" class="form-control-custom"
                                   value="<?= date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Select Crop</label>
                            <select name="crop_id" class="form-control-custom" id="cropSelect" onchange="updateWaterReq(this)">
                                <option value="">-- No specific crop --</option>
                                <?php foreach ($crops as $c): ?>
                                <option value="<?= $c['id'] ?>" data-req="<?= $c['water_requirement'] ?? 400 ?>" data-area="<?= $c['area_planted'] ?>">
                                    <?= e($c['crop_name']) ?> (<?= $c['area_planted'] ?> acres)
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Irrigation Method *</label>
                            <select name="irrigation_method" class="form-control-custom" required>
                                <option value="">Select method...</option>
                                <option value="drip">💧 Drip Irrigation</option>
                                <option value="sprinkler">🌧️ Sprinkler</option>
                                <option value="flood">🌊 Flood Irrigation</option>
                                <option value="furrow">🌾 Furrow</option>
                                <option value="subsurface">⬇️ Subsurface</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Duration (minutes) *</label>
                            <input type="number" name="duration_minutes" class="form-control-custom"
                                   placeholder="e.g. 120" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Amount Used (liters) *</label>
                            <input type="number" name="amount_used" class="form-control-custom" id="amountInput"
                                   placeholder="e.g. 4500" step="0.01" min="1" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Area Irrigated (acres)</label>
                            <input type="number" name="area_irrigated" class="form-control-custom"
                                   placeholder="e.g. 10.5" step="0.01" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Pump Power (HP)</label>
                            <input type="number" name="pump_power" class="form-control-custom"
                                   placeholder="e.g. 5" step="0.01" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Notes</label>
                            <input type="text" name="notes" class="form-control-custom"
                                   placeholder="Any additional notes...">
                        </div>
                    </div>

                    <div class="form-actions mt-4">
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save me-2"></i>Save Record
                        </button>
                        <a href="<?= APP_URL ?>/water" class="btn-outline-custom ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Water Requirement Info -->
        <div class="card-custom mb-4" id="cropInfoCard" style="display:none;">
            <div class="card-header-custom">
                <h3 class="card-title-custom"><i class="fas fa-seedling me-2 text-success"></i>Crop Water Need</h3>
            </div>
            <div class="card-body-custom">
                <div class="info-metric">
                    <span class="info-label">Recommended / Day</span>
                    <span class="info-value" id="reqValue">—</span>
                </div>
                <div class="info-metric">
                    <span class="info-label">For your area</span>
                    <span class="info-value" id="totalReqValue">—</span>
                </div>
            </div>
        </div>

        <!-- Quick Tips -->
        <div class="card-custom">
            <div class="card-header-custom">
                <h3 class="card-title-custom"><i class="fas fa-lightbulb me-2 text-warning"></i>Quick Tips</h3>
            </div>
            <div class="card-body-custom">
                <div class="tip-item">💧 Drip irrigation saves up to 60% water vs flood.</div>
                <div class="tip-item">⏰ Water in early morning to reduce evaporation.</div>
                <div class="tip-item">🌱 Match water amount to crop growth stage.</div>
                <div class="tip-item">📊 Log daily for accurate analytics.</div>
            </div>
        </div>

        <!-- Farm Summary -->
        <div class="card-custom mt-4">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Farm Info</h3>
            </div>
            <div class="card-body-custom">
                <div class="farm-info-mini">
                    <div class="finfo-row"><span>Farm</span><strong><?= e($farmer['farm_name']) ?></strong></div>
                    <div class="finfo-row"><span>Size</span><strong><?= $farmer['farm_size'] ?> acres</strong></div>
                    <div class="finfo-row"><span>Soil</span><strong><?= ucfirst($farmer['soil_type']) ?></strong></div>
                    <div class="finfo-row"><span>Source</span><strong><?= ucfirst($farmer['water_source']) ?></strong></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateWaterReq(sel) {
    const opt = sel.options[sel.selectedIndex];
    const card = document.getElementById('cropInfoCard');
    if (sel.value) {
        const req = parseFloat(opt.dataset.req) || 400;
        const area = parseFloat(opt.dataset.area) || 1;
        document.getElementById('reqValue').textContent = req + ' L/acre';
        document.getElementById('totalReqValue').textContent = (req * area).toLocaleString() + ' L';
        document.getElementById('amountInput').placeholder = Math.round(req * area) + ' (suggested)';
        card.style.display = 'block';
    } else {
        card.style.display = 'none';
    }
}
</script>
