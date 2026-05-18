<?php
// ============================================================
// app/controllers/AdminController.php - Admin Dashboard
// ============================================================

class AdminController extends Controller {

    public function dashboard() {
        $this->requireAdmin();

        $farmerModel  = $this->model('FarmerModel');
        $waterModel   = $this->model('WaterUsageModel');
        $userModel    = $this->model('UserModel');
        $cropModel    = $this->model('CropModel');
        $irrigModel   = $this->model('IrrigationModel');

        // Global stats
        $stats = [
            'total_farmers'     => $farmerModel->getTotalCount(),
            'total_users'       => $userModel->count(),
            'total_water_usage' => $waterModel->getTotalUsage(),
            'month_usage'       => $waterModel->getMonthUsage(),
            'today_usage'       => $waterModel->getTodayUsage(),
            'active_crops'      => $cropModel->getTotalActive(),
        ];

        // Chart data
        $monthlyUsage   = $waterModel->getGlobalMonthlyUsage(6);
        $cropWiseUsage  = $waterModel->getCropWiseUsage();
        $methodUsage    = $waterModel->getMethodWiseUsage();

        // Recent records
        $recentUsage = $waterModel->getAllWithDetails(10);

        // Farmers list
        $farmers = $farmerModel->getAllWithStats();

        // Upcoming irrigation
        $upcomingIrrigation = $irrigModel->getUpcomingAll(5);

        $flash = $this->getFlash();

        $this->render('dashboard.admin', [
            'title'              => 'Admin Dashboard',
            'stats'              => $stats,
            'monthlyUsage'       => $monthlyUsage,
            'cropWiseUsage'      => $cropWiseUsage,
            'methodUsage'        => $methodUsage,
            'recentUsage'        => $recentUsage,
            'farmers'            => $farmers,
            'upcomingIrrigation' => $upcomingIrrigation,
            'flash'              => $flash,
        ], 'main');
    }

    // List all farmers
    public function farmers() {
        $this->requireAdmin();

        $farmerModel = $this->model('FarmerModel');
        $farmers = $farmerModel->getAllWithStats();
        $flash = $this->getFlash();

        $this->render('farmers.list', [
            'title'   => 'Manage Farmers',
            'farmers' => $farmers,
            'flash'   => $flash,
        ], 'main');
    }

    // View single farmer
    public function viewFarmer($farmerId) {
        $this->requireAdmin();

        $farmerModel = $this->model('FarmerModel');
        $waterModel  = $this->model('WaterUsageModel');
        $cropModel   = $this->model('CropModel');

        $farmer = $farmerModel->findById($farmerId);
        if (!$farmer) {
            $this->setFlash(ALERT_DANGER, 'Farmer not found.');
            $this->redirect('admin/farmers');
        }

        $stats = $farmerModel->getStats($farmerId);
        $dailyUsage = $farmerModel->getDailyUsage($farmerId, 30);
        $crops = $cropModel->getByFarmer($farmerId);
        $recentUsage = $waterModel->getByFarmer($farmerId, 10);

        $this->render('farmers.view', [
            'title'       => 'Farmer Details',
            'farmer'      => $farmer,
            'stats'       => $stats,
            'dailyUsage'  => $dailyUsage,
            'crops'       => $crops,
            'recentUsage' => $recentUsage,
        ], 'main');
    }

    // Delete farmer
    public function deleteFarmer($userId) {
        $this->requireAdmin();

        if ($userId == $_SESSION['user_id']) {
            $this->setFlash(ALERT_DANGER, 'You cannot delete your own account.');
            $this->redirect('admin/farmers');
        }

        $userModel = $this->model('UserModel');
        $userModel->delete($userId);
        $this->setFlash(ALERT_SUCCESS, 'Farmer deleted successfully.');
        $this->redirect('admin/farmers');
    }

    // Toggle farmer status
    public function toggleFarmer($userId) {
        $this->requireAdmin();
        $userModel = $this->model('UserModel');
        $userModel->toggleStatus($userId);
        $this->setFlash(ALERT_SUCCESS, 'Farmer status updated.');
        $this->redirect('admin/farmers');
    }

    // All water usage records
    public function waterUsage() {
        $this->requireAdmin();

        $waterModel = $this->model('WaterUsageModel');
        $records = $waterModel->getAllWithDetails(50);
        $flash = $this->getFlash();

        $this->render('water.list', [
            'title'   => 'All Water Usage Records',
            'records' => $records,
            'flash'   => $flash,
            'isAdmin' => true,
        ], 'main');
    }

    // Reports page
    public function reports() {
        $this->requireAdmin();

        $waterModel  = $this->model('WaterUsageModel');
        $farmerModel = $this->model('FarmerModel');

        $monthlyUsage = $waterModel->getGlobalMonthlyUsage(12);
        $cropUsage    = $waterModel->getCropWiseUsage();
        $methodUsage  = $waterModel->getMethodWiseUsage();
        $farmers      = $farmerModel->getAllWithStats();

        $this->render('reports.index', [
            'title'        => 'Reports & Analytics',
            'monthlyUsage' => $monthlyUsage,
            'cropUsage'    => $cropUsage,
            'methodUsage'  => $methodUsage,
            'farmers'      => $farmers,
        ], 'main');
    }
}
