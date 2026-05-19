<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'AquaFarm') ?> | AquaFarm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= APP_URL ?>/css/style.css" rel="stylesheet">
</head>
<body class="auth-body">

    <!-- Animated background -->
    <div class="auth-bg">
        <div class="water-wave wave1"></div>
        <div class="water-wave wave2"></div>
        <div class="water-wave wave3"></div>
        <div class="floating-drops">
            <?php for($i=0;$i<8;$i++): ?>
            <div class="drop drop-<?=$i?>"><i class="fas fa-tint"></i></div>
            <?php endfor; ?>
        </div>
    </div>

    <!-- Auth Container -->
    <div class="auth-container">
        <div class="auth-brand">
            <div class="auth-logo"><i class="fas fa-tint"></i></div>
            <h1 class="auth-title">AquaFarm</h1>
            <p class="auth-subtitle">Water Usage Optimization Dashboard</p>
        </div>

        <?php if (!empty($flash)): ?>
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show" role="alert">
            <i class="fas fa-info-circle me-2"></i>
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php require_once $content; ?>

        <div class="auth-footer">
            <p>&copy; <?= date('Y') ?> AquaFarm &mdash; Smart Water Management for Agriculture</p>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
