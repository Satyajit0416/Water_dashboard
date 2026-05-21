<div class="auth-card">
    <h2 class="auth-card-title">Welcome Back</h2>
    <p class="auth-card-sub">Sign in to your AquaFarm account</p>
    <form action="<?= APP_URL ?>/auth/doLogin" method="POST" class="auth-form">
        <?= csrfField() ?>
        <div class="form-group-custom">
            <label class="form-label-custom">Email Address</label>
            <div class="input-with-icon">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" class="form-control-custom" 
                       placeholder="your@email.com" required autocomplete="email">
            </div>
        </div>
        <div class="form-group-custom">
            <label class="form-label-custom">Password</label>
            <div class="input-with-icon">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" class="form-control-custom" 
                       placeholder="Enter password" required id="loginPass">
                <button type="button" class="input-toggle" onclick="togglePass('loginPass', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn-auth">
            <i class="fas fa-sign-in-alt me-2"></i>Sign In
        </button>
    </form>
    <p class="auth-switch">
        Don't have an account? <a href="<?= APP_URL ?>/auth/register">Register here</a>
    </p>
</div>
<script>
function togglePass(id, btn) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
    btn.querySelector('i').className = inp.type === 'password' ? 'fas fa-eye' : 'fas fa-eye-slash';
}
</script>
