<?php
// ============================================================
// app/controllers/HomeController.php
// ============================================================

class HomeController extends Controller {
    public function index() {
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_role'] === 'admin') {
                $this->redirect('admin/dashboard');
            } else {
                $this->redirect('dashboard');
            }
        } else {
            $this->redirect('auth/login');
        }
    }
}
