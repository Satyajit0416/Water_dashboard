<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'Dashboard') ?> | AquaFarm</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?= APP_URL ?>/css/style.css" rel="stylesheet">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-tint"></i>
        </div>
        <div class="brand-text">
            <span class="brand-name">AquaFarm</span>
            <span class="brand-sub">Water Dashboard</span>
        </div>
    </div>

    <div class="sidebar-user">
        <div class="user-avatar">
            <?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
        </div>
        <div class="user-info">
            <span class="user-name"><?= e($_SESSION['user_name'] ?? '') ?></span>
            <span class="user-role badge"><?= ucfirst($_SESSION['user_role'] ?? '') ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
        <!-- Admin Navigation -->
        <div class="nav-section-title">Admin Panel</div>
        <a href="<?= APP_URL ?>/admin/dashboard" class="nav-item <?= isActive('admin/dashboard') ?>">
            <i class="fas fa-chart-pie"></i><span>Dashboard</span>
        </a>
        <a href="<?= APP_URL ?>/admin/farmers" class="nav-item <?= isActive('admin/farmers') ?>">
            <i class="fas fa-users"></i><span>Farmers</span>
        </a>
        <a href="<?= APP_URL ?>/admin/waterUsage" class="nav-item <?= isActive('admin/waterUsage') ?>">
            <i class="fas fa-water"></i><span>Water Records</span>
        </a>
        <a href="<?= APP_URL ?>/admin/reports" class="nav-item <?= isActive('admin/reports') ?>">
            <i class="fas fa-file-chart-line"></i><span>Reports</span>
        </a>
        <?php else: ?>
        <!-- Farmer Navigation -->
        <div class="nav-section-title">My Farm</div>
        <a href="<?= APP_URL ?>/dashboard" class="nav-item <?= isActive('dashboard') && !isActive('dashboard/profile') ? 'active' : '' ?>">
            <i class="fas fa-home"></i><span>Dashboard</span>
        </a>
        <a href="<?= APP_URL ?>/water" class="nav-item <?= isActive('water') ?>">
            <i class="fas fa-tint"></i><span>Water Usage</span>
        </a>
        <a href="<?= APP_URL ?>/water/add" class="nav-item <?= isActive('water/add') ?>">
            <i class="fas fa-plus-circle"></i><span>Log Usage</span>
        </a>
        <a href="<?= APP_URL ?>/crop" class="nav-item <?= isActive('crop') ?>">
            <i class="fas fa-seedling"></i><span>My Crops</span>
        </a>
        <a href="<?= APP_URL ?>/irrigation" class="nav-item <?= isActive('irrigation') ?>">
            <i class="fas fa-clock"></i><span>Schedule</span>
        </a>
        <div class="nav-section-title">Account</div>
        <a href="<?= APP_URL ?>/dashboard/profile" class="nav-item <?= isActive('dashboard/profile') ?>">
            <i class="fas fa-user-cog"></i><span>Profile</span>
        </a>
        <?php endif; ?>

        <div class="nav-section-title">System</div>
        <a href="<?= APP_URL ?>/auth/logout" class="nav-item text-danger">
            <i class="fas fa-sign-out-alt"></i><span>Logout</span>
        </a>
    </nav>

    <!-- Water level indicator -->
    <div class="sidebar-footer">
        <div class="water-level-indicator">
            <div class="wl-label">
                <i class="fas fa-tint me-1"></i>System Status
            </div>
            <div class="wl-bar">
                <div class="wl-fill" style="width: 72%"></div>
            </div>
            <div class="wl-value">72% Optimal</div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <!-- Top Navigation -->
    <header class="topbar">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <div class="topbar-search">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search...">
        </div>

        <div class="topbar-actions">
            <!-- Dark Mode Toggle -->
            <button class="action-btn" id="darkModeToggle" title="Toggle theme">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>

            <!-- Notifications -->
            <button class="action-btn position-relative" title="Notifications">
                <i class="fas fa-bell"></i>
                <span class="notification-dot"></span>
            </button>

            <!-- User dropdown -->
            <div class="user-chip">
                <div class="chip-avatar"><?= strtoupper(substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?></div>
                <span><?= e($_SESSION['user_name'] ?? '') ?></span>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <?php if (!empty($flash)): ?>
    <div class="flash-container">
        <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show flash-alert" role="alert">
            <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'danger' ? 'exclamation-circle' : 'info-circle') ?> me-2"></i>
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Page Content -->
    <div class="content-area">
        <?php require_once $content; ?>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="<?= APP_URL ?>/js/main.js"></script>
</body>
</html>
