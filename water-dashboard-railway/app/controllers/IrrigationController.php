<?php
// ============================================================
// app/controllers/IrrigationController.php
// ============================================================

class IrrigationController extends Controller {

    public function index() {
        $this->requireFarmer();

        $farmerModel    = $this->model('FarmerModel');
        $irrigModel     = $this->model('IrrigationModel');
        $cropModel      = $this->model('CropModel');

        $farmer    = $farmerModel->getByUserId($_SESSION['user_id']);
        $schedules = $irrigModel->getByFarmer($farmer['id']);
        $crops     = $cropModel->getActiveCrops($farmer['id']);
        $flash     = $this->getFlash();

        $this->render('water.irrigation', [
            'title'     => 'Irrigation Schedule',
            'schedules' => $schedules,
            'crops'     => $crops,
            'farmer'    => $farmer,
            'flash'     => $flash,
        ], 'main');
    }

    public function store() {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $irrigModel  = $this->model('IrrigationModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);

        $data = [
            'farmer_id'         => $farmer['id'],
            'crop_id'           => intval($this->getPost('crop_id')) ?: null,
            'scheduled_date'    => $this->getPost('scheduled_date'),
            'scheduled_time'    => $this->getPost('scheduled_time'),
            'duration_minutes'  => intval($this->getPost('duration_minutes')),
            'irrigation_method' => $this->getPost('irrigation_method'),
            'estimated_water'   => floatval($this->getPost('estimated_water')),
            'notes'             => htmlspecialchars($this->getPost('notes'), ENT_QUOTES, 'UTF-8'),
        ];

        $irrigModel->add($data);
        $this->setFlash(ALERT_SUCCESS, 'Schedule added successfully!');
        $this->redirect('irrigation');
    }

    public function updateStatus($id) {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $irrigModel  = $this->model('IrrigationModel');
        $farmer      = $farmerModel->getByUserId($_SESSION['user_id']);

        $schedule = $irrigModel->findById($id);
        if (!$schedule || $schedule['farmer_id'] !== $farmer['id']) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'error' => 'Forbidden'], 403);
            }
            $this->setFlash(ALERT_DANGER, 'Access denied.');
            $this->redirect('irrigation');
        }

        $status = $this->getPost('status');
        $irrigModel->updateStatus($id, $status);

        if ($this->isAjax()) {
            $this->json(['success' => true]);
        }
        $this->setFlash(ALERT_SUCCESS, 'Schedule status updated.');
        $this->redirect('irrigation');
    }

    public function delete($id) {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $irrigModel  = $this->model('IrrigationModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);

        $irrigModel->deleteSchedule($id, $farmer['id']);
        $this->setFlash(ALERT_SUCCESS, 'Schedule deleted.');
        $this->redirect('irrigation');
    }

    private function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
