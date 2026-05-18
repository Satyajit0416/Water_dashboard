<div class="page-header">
    <div><h1 class="page-title">Add New Crop</h1><p class="page-subtitle">Register a crop on your farm</p></div>
    <a href="<?= APP_URL ?>/crop" class="btn-outline-custom"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>
<div class="row"><div class="col-lg-8">
    <div class="card-custom">
        <div class="card-header-custom"><h3 class="card-title-custom"><i class="fas fa-seedling me-2 text-success"></i>Crop Details</h3></div>
        <div class="card-body-custom">
            <form action="<?= APP_URL ?>/crop/store" method="POST" class="form-custom">
                <?= csrfField() ?>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Crop Name *</label>
                        <input type="text" name="crop_name" class="form-control-custom" placeholder="e.g. Wheat" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Crop Type *</label>
                        <select name="crop_type" class="form-control-custom" required onchange="suggestWater(this)">
                            <option value="">Select type...</option>
                            <option value="cereal">🌾 Cereal</option>
                            <option value="vegetable">🥦 Vegetable</option>
                            <option value="fruit">🍎 Fruit</option>
                            <option value="pulse">🫘 Pulse</option>
                            <option value="oilseed">🌻 Oilseed</option>
                            <option value="cash_crop">💰 Cash Crop</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Area Planted (acres) *</label>
                        <input type="number" name="area_planted" class="form-control-custom" step="0.01" min="0.1" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Water Requirement (L/acre/day) *</label>
                        <input type="number" name="water_requirement" id="waterReq" class="form-control-custom" step="0.01" min="1" placeholder="400" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Planting Date *</label>
                        <input type="date" name="planting_date" class="form-control-custom" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Expected Harvest *</label>
                        <input type="date" name="expected_harvest" class="form-control-custom" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Growth Stage</label>
                        <select name="growth_stage" class="form-control-custom">
                            <option value="seedling">Seedling</option>
                            <option value="vegetative">Vegetative</option>
                            <option value="flowering">Flowering</option>
                            <option value="fruiting">Fruiting</option>
                            <option value="harvest">Harvest</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions mt-4">
                    <button type="submit" class="btn-primary-custom"><i class="fas fa-save me-2"></i>Add Crop</button>
                    <a href="<?= APP_URL ?>/crop" class="btn-outline-custom ms-2">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div></div>
<script>
const waterNeeds = {cereal:450,vegetable:300,fruit:350,pulse:250,oilseed:400,cash_crop:700};
function suggestWater(sel) {
    const req = waterNeeds[sel.value];
    if (req) document.getElementById('waterReq').value = req;
}
</script>
