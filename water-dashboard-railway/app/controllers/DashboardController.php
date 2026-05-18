<?php
// ============================================================
// app/controllers/DashboardController.php - Farmer Dashboard
// ============================================================

class DashboardController extends Controller {

    public function index() {
        $this->requireFarmer();

        $farmerModel    = $this->model('FarmerModel');
        $waterModel     = $this->model('WaterUsageModel');
        $cropModel      = $this->model('CropModel');
        $irrigationModel = $this->model('IrrigationModel');

        // Get farmer profile
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        if (!$farmer) {
            $this->setFlash(ALERT_DANGER, 'Farmer profile not found.');
            $this->redirect('auth/logout');
        }

        $farmerId = $farmer['id'];

        // Stats
        $stats = [
            'today_usage'     => $waterModel->getTodayUsage($farmerId),
            'month_usage'     => $waterModel->getMonthUsage($farmerId),
            'total_usage'     => $waterModel->getTotalUsage(),
            'active_crops'    => $cropModel->count('farmer_id = ? AND status = ?', [$farmerId, 'active']),
            'pending_schedules' => $irrigationModel->countPending($farmerId),
        ];

        // Chart data - last 7 days
        $dailyUsage = $farmerModel->getDailyUsage($farmerId, 7);
        $monthlyUsage = $farmerModel->getMonthlyUsage($farmerId, 6);

        // Crop-wise usage
        $cropWiseUsage = $waterModel->getCropWiseUsage($farmerId);

        // Method-wise usage
        $methodUsage = $waterModel->getMethodWiseUsage($farmerId);

        // Recent usage records
        $recentUsage = $waterModel->getByFarmer($farmerId, 5);

        // Active crops
        $crops = $cropModel->getActiveCrops($farmerId);

        // Upcoming schedules
        $schedules = $irrigationModel->getByFarmer($farmerId, true);

        // Water saving suggestions
        $suggestions = [];
        if ($stats['today_usage'] > 0 && count($crops) > 0) {
            $avgUsagePerAcre = $stats['today_usage'] / max($farmer['farm_size'], 1);
            $suggestions = getWaterSuggestion('drip', $avgUsagePerAcre, 'general');
        } else {
            $suggestions = ['💧 Log your water usage to get personalized saving tips!'];
        }

        $flash = $this->getFlash();

        $this->render('dashboard.farmer', [
            'title'          => 'My Dashboard',
            'farmer'         => $farmer,
            'stats'          => $stats,
            'dailyUsage'     => $dailyUsage,
            'monthlyUsage'   => $monthlyUsage,
            'cropWiseUsage'  => $cropWiseUsage,
            'methodUsage'    => $methodUsage,
            'recentUsage'    => $recentUsage,
            'crops'          => $crops,
            'schedules'      => $schedules,
            'suggestions'    => $suggestions,
            'flash'          => $flash,
        ], 'main');
    }

    // Profile page
    public function profile() {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $userModel   = $this->model('UserModel');

        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $flash  = $this->getFlash();

        $this->render('farmers.profile', [
            'title'  => 'My Profile',
            'farmer' => $farmer,
            'flash'  => $flash,
        ], 'main');
    }

    // Update profile
    public function updateProfile() {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $userModel   = $this->model('UserModel');

        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);

        // Update user info
        $userModel->updateProfile($_SESSION['user_id'], [
            'name'  => htmlspecialchars($this->getPost('name'), ENT_QUOTES, 'UTF-8'),
            'email' => filter_var($this->getPost('email'), FILTER_SANITIZE_EMAIL),
        ]);

        // Update farmer info
        $farmerModel->update($farmer['id'], [
            'farm_name'    => htmlspecialchars($this->getPost('farm_name'), ENT_QUOTES, 'UTF-8'),
            'location'     => htmlspecialchars($this->getPost('location'), ENT_QUOTES, 'UTF-8'),
            'farm_size'    => floatval($this->getPost('farm_size')),
            'soil_type'    => $this->getPost('soil_type'),
            'water_source' => $this->getPost('water_source'),
            'phone'        => htmlspecialchars($this->getPost('phone'), ENT_QUOTES, 'UTF-8'),
        ]);

        // Update session name
        $_SESSION['user_name'] = htmlspecialchars($this->getPost('name'), ENT_QUOTES, 'UTF-8');

        // Change password if provided
        $newPass = $this->getPost('new_password');
        if (!empty($newPass)) {
            if ($newPass === $this->getPost('confirm_password')) {
                $userModel->changePassword($_SESSION['user_id'], $newPass);
            }
        }

        $this->setFlash(ALERT_SUCCESS, 'Profile updated successfully!');
        $this->redirect('dashboard/profile');
    }
}
