<?php
// ============================================================
// app/core/Helper.php - Global Helper Functions
// ============================================================

// Format number with units
function formatLiters($liters) {
    if ($liters >= 1000000) return round($liters / 1000000, 2) . ' ML';
    if ($liters >= 1000) return round($liters / 1000, 2) . ' KL';
    return round($liters, 2) . ' L';
}

// Format date nicely
function formatDate($date, $format = 'd M Y') {
    return date($format, strtotime($date));
}

// Time ago
function timeAgo($datetime) {
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);
    if ($diff->d > 0) return $diff->d . 'd ago';
    if ($diff->h > 0) return $diff->h . 'h ago';
    if ($diff->i > 0) return $diff->i . 'm ago';
    return 'Just now';
}

// Get alert class for water usage
function getUsageAlert($used, $recommended) {
    $ratio = $recommended > 0 ? ($used / $recommended) : 0;
    if ($ratio > 1.3) return 'danger';
    if ($ratio > 1.1) return 'warning';
    return 'success';
}

// Generate water saving suggestion
function getWaterSuggestion($method, $usedPerAcre, $cropType) {
    $suggestions = [];
    
    if ($usedPerAcre > WATER_THRESHOLD_CRITICAL) {
        $suggestions[] = '🔴 Critical: Immediately reduce irrigation. Usage is extremely high.';
    } elseif ($usedPerAcre > WATER_THRESHOLD_HIGH) {
        $suggestions[] = '🟡 High usage detected. Reduce irrigation duration by 20%.';
    }
    
    if ($method === 'flood') {
        $suggestions[] = '💧 Switch to drip irrigation to save up to 60% water.';
    } elseif ($method === 'sprinkler') {
        $suggestions[] = '💧 Consider drip irrigation for more efficient water usage.';
    }
    
    if (empty($suggestions)) {
        $suggestions[] = '✅ Water usage is optimal. Keep it up!';
    }
    
    return $suggestions;
}

// Crop water requirement (liters/acre/day)
function getCropWaterRequirement($cropType) {
    $requirements = [
        'cereal'    => 450,
        'vegetable' => 300,
        'fruit'     => 350,
        'pulse'     => 250,
        'oilseed'   => 400,
        'cash_crop' => 700,
    ];
    return $requirements[$cropType] ?? 400;
}

// Get badge HTML for status
function statusBadge($status) {
    $badges = [
        'active'      => '<span class="badge bg-success">Active</span>',
        'harvested'   => '<span class="badge bg-info">Harvested</span>',
        'failed'      => '<span class="badge bg-danger">Failed</span>',
        'pending'     => '<span class="badge bg-warning text-dark">Pending</span>',
        'completed'   => '<span class="badge bg-success">Completed</span>',
        'skipped'     => '<span class="badge bg-secondary">Skipped</span>',
        'rescheduled' => '<span class="badge bg-info">Rescheduled</span>',
    ];
    return $badges[$status] ?? '<span class="badge bg-secondary">' . ucfirst($status) . '</span>';
}

// Irrigation method icon
function irrigationIcon($method) {
    $icons = [
        'drip'       => '💧',
        'sprinkler'  => '🌧️',
        'flood'      => '🌊',
        'furrow'     => '🌾',
        'subsurface' => '⬇️',
    ];
    return $icons[$method] ?? '💧';
}

// CSRF token field HTML
function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

// Pagination HTML
function paginationHtml($currentPage, $totalPages, $baseUrl) {
    if ($totalPages <= 1) return '';
    $html = '<nav><ul class="pagination pagination-sm mb-0">';
    $html .= '<li class="page-item ' . ($currentPage == 1 ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage - 1) . '">Previous</a></li>';
    for ($i = 1; $i <= $totalPages; $i++) {
        $html .= '<li class="page-item ' . ($i == $currentPage ? 'active' : '') . '">';
        $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a></li>';
    }
    $html .= '<li class="page-item ' . ($currentPage == $totalPages ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . $baseUrl . '?page=' . ($currentPage + 1) . '">Next</a></li>';
    $html .= '</ul></nav>';
    return $html;
}

// Sanitize output
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// Check if current URL matches
function isActive($path) {
    $url = isset($_GET['url']) ? $_GET['url'] : '';
    return strpos($url, $path) === 0 ? 'active' : '';
}

// Asset URL
function asset($path) {
    return APP_URL . '/../public/' . ltrim($path, '/');
}
