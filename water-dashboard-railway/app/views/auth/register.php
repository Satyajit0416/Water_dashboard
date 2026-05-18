<div class="auth-card auth-card-wide">
    <h2 class="auth-card-title">Create Account</h2>
    <p class="auth-card-sub">Join AquaFarm and start optimizing water usage</p>

    <form action="<?= APP_URL ?>/auth/doRegister" method="POST" class="auth-form">
        <?= csrfField() ?>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Full Name *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="name" class="form-control-custom" placeholder="Rajesh Kumar" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Email Address *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" class="form-control-custom" placeholder="you@email.com" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Password *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" id="regPass" class="form-control-custom" placeholder="Min 6 characters" required>
                        <button type="button" class="input-toggle" onclick="togglePass('regPass', this)">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Confirm Password *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="confirm_password" class="form-control-custom" placeholder="Repeat password" required>
                    </div>
                </div>
            </div>

            <div class="col-12"><hr class="form-divider"><p class="section-label">Farm Details</p></div>

            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Farm Name *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-home"></i>
                        <input type="text" name="farm_name" class="form-control-custom" placeholder="Green Acres Farm" required>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group-custom">
                    <label class="form-label-custom">Location *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-map-marker-alt"></i>
                        <input type="text" name="location" class="form-control-custom" placeholder="Punjab, India" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Farm Size (acres) *</label>
                    <div class="input-with-icon">
                        <i class="fas fa-ruler-combined"></i>
                        <input type="number" name="farm_size" class="form-control-custom" placeholder="25.5" step="0.01" min="0.1" required>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Soil Type</label>
                    <div class="input-with-icon">
                        <i class="fas fa-layer-group"></i>
                        <select name="soil_type" class="form-control-custom">
                            <option value="loamy">Loamy</option>
                            <option value="clay">Clay</option>
                            <option value="sandy">Sandy</option>
                            <option value="silty">Silty</option>
                            <option value="peaty">Peaty</option>
                            <option value="chalky">Chalky</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group-custom">
                    <label class="form-label-custom">Water Source</label>
                    <div class="input-with-icon">
                        <i class="fas fa-water"></i>
                        <select name="water_source" class="form-control-custom">
                            <option value="borewell">Borewell</option>
                            <option value="canal">Canal</option>
                            <option value="rainwater">Rainwater</option>
                            <option value="river">River</option>
                            <option value="tank">Tank</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group-custom">
                    <label class="form-label-custom">Phone Number</label>
                    <div class="input-with-icon">
                        <i class="fas fa-phone"></i>
                        <input type="tel" name="phone" class="form-control-custom" placeholder="9876543210">
                    </div>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-auth mt-3">
            <i class="fas fa-user-plus me-2"></i>Create Account
        </button>
    </form>

    <p class="auth-switch">
        Already have an account? <a href="<?= APP_URL ?>/auth/login">Sign in</a>
    </p>
</div>

<script>
function togglePass(id, btn) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    btn.querySelector('i').className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
