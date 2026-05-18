<?php
// ============================================================
// app/controllers/WaterController.php
// ============================================================

class WaterController extends Controller {

    // List farmer's water usage
    public function index() {
        $this->requireAuth();

        $farmerModel = $this->model('FarmerModel');
        $waterModel  = $this->model('WaterUsageModel');
        $cropModel   = $this->model('CropModel');

        $farmer  = $farmerModel->getByUserId($_SESSION['user_id']);
        $filters = $this->getGet();

        $records = $waterModel->search($farmer['id'], $filters);
        $crops   = $cropModel->getActiveCrops($farmer['id']);
        $stats   = [
            'today'  => $waterModel->getTodayUsage($farmer['id']),
            'month'  => $waterModel->getMonthUsage($farmer['id']),
        ];
        $flash = $this->getFlash();

        $this->render('water.index', [
            'title'   => 'Water Usage Records',
            'records' => $records,
            'crops'   => $crops,
            'farmer'  => $farmer,
            'stats'   => $stats,
            'filters' => $filters,
            'flash'   => $flash,
        ], 'main');
    }

    // Show add form
    public function add() {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $cropModel   = $this->model('CropModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $crops  = $cropModel->getActiveCrops($farmer['id']);
        $flash  = $this->getFlash();
        $this->generateCsrf();

        $this->render('water.add', [
            'title'  => 'Log Water Usage',
            'crops'  => $crops,
            'farmer' => $farmer,
            'flash'  => $flash,
        ], 'main');
    }

    // Save new water usage
    public function store() {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $waterModel  = $this->model('WaterUsageModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);

        $data = [
            'farmer_id'          => $farmer['id'],
            'crop_id'            => intval($this->getPost('crop_id')) ?: null,
            'usage_date'         => $this->getPost('usage_date'),
            'amount_used'        => floatval($this->getPost('amount_used')),
            'irrigation_method'  => $this->getPost('irrigation_method'),
            'duration_minutes'   => intval($this->getPost('duration_minutes')),
            'pump_power'         => floatval($this->getPost('pump_power')) ?: null,
            'area_irrigated'     => floatval($this->getPost('area_irrigated')) ?: null,
            'notes'              => htmlspecialchars($this->getPost('notes'), ENT_QUOTES, 'UTF-8'),
        ];

        // Validate
        if (empty($data['usage_date']) || $data['amount_used'] <= 0 || $data['duration_minutes'] <= 0) {
            $this->setFlash(ALERT_DANGER, 'Please fill all required fields correctly.');
            $this->redirect('water/add');
        }

        $id = $waterModel->add($data);

        if ($id) {
            $this->setFlash(ALERT_SUCCESS, 'Water usage logged successfully!');
            $this->redirect('water');
        } else {
            $this->setFlash(ALERT_DANGER, 'Failed to save record. Try again.');
            $this->redirect('water/add');
        }
    }

    // Show edit form
    public function edit($id) {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $waterModel  = $this->model('WaterUsageModel');
        $cropModel   = $this->model('CropModel');

        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $record = $waterModel->findById($id);

        // Verify ownership
        if (!$record || $record['farmer_id'] != $farmer['id']) {
            $this->setFlash(ALERT_DANGER, 'Record not found or access denied.');
            $this->redirect('water');
        }

        $crops = $cropModel->getActiveCrops($farmer['id']);
        $this->generateCsrf();

        $this->render('water.edit', [
            'title'  => 'Edit Water Usage',
            'record' => $record,
            'crops'  => $crops,
        ], 'main');
    }

    // Update water usage
    public function update($id) {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $waterModel  = $this->model('WaterUsageModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $record = $waterModel->findById($id);

        if (!$record || $record['farmer_id'] != $farmer['id']) {
            $this->setFlash(ALERT_DANGER, 'Access denied.');
            $this->redirect('water');
        }

        $data = [
            'crop_id'           => intval($this->getPost('crop_id')) ?: null,
            'usage_date'        => $this->getPost('usage_date'),
            'amount_used'       => floatval($this->getPost('amount_used')),
            'irrigation_method' => $this->getPost('irrigation_method'),
            'duration_minutes'  => intval($this->getPost('duration_minutes')),
            'pump_power'        => floatval($this->getPost('pump_power')) ?: null,
            'area_irrigated'    => floatval($this->getPost('area_irrigated')) ?: null,
            'notes'             => htmlspecialchars($this->getPost('notes'), ENT_QUOTES, 'UTF-8'),
        ];

        $waterModel->update($id, $data);
        $this->setFlash(ALERT_SUCCESS, 'Record updated successfully!');
        $this->redirect('water');
    }

    // Delete usage record
    public function delete($id) {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $waterModel  = $this->model('WaterUsageModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $record = $waterModel->findById($id);

        if (!$record || $record['farmer_id'] != $farmer['id']) {
            $this->setFlash(ALERT_DANGER, 'Access denied.');
            $this->redirect('water');
        }

        $waterModel->delete($id);
        $this->setFlash(ALERT_SUCCESS, 'Record deleted.');
        $this->redirect('water');
    }

    // AJAX: Get chart data
    public function chartData() {
        $this->requireAuth();

        $farmerModel = $this->model('FarmerModel');
        $waterModel  = $this->model('WaterUsageModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);

        $type = $this->getGet('type', 'daily');

        if ($type === 'monthly') {
            $data = $farmerModel->getMonthlyUsage($farmer['id'], 6);
        } else {
            $data = $farmerModel->getDailyUsage($farmer['id'], 7);
        }

        $this->json(['success' => true, 'data' => $data]);
    }
}
