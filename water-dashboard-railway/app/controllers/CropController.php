<?php
// ============================================================
// app/controllers/CropController.php
// ============================================================

class CropController extends Controller {

    public function index() {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $cropModel   = $this->model('CropModel');

        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $crops  = $cropModel->getByFarmer($farmer['id']);
        $flash  = $this->getFlash();

        $this->render('farmers.crops', [
            'title'  => 'My Crops',
            'crops'  => $crops,
            'farmer' => $farmer,
            'flash'  => $flash,
        ], 'main');
    }

    public function add() {
        $this->requireFarmer();
        $this->generateCsrf();
        $farmerModel = $this->model('FarmerModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);

        $this->render('farmers.addcrop', [
            'title'  => 'Add Crop',
            'farmer' => $farmer,
            'flash'  => $this->getFlash(),
        ], 'main');
    }

    public function store() {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $cropModel   = $this->model('CropModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);

        $data = [
            'farmer_id'        => $farmer['id'],
            'crop_name'        => htmlspecialchars($this->getPost('crop_name'), ENT_QUOTES, 'UTF-8'),
            'crop_type'        => $this->getPost('crop_type'),
            'area_planted'     => floatval($this->getPost('area_planted')),
            'planting_date'    => $this->getPost('planting_date'),
            'expected_harvest' => $this->getPost('expected_harvest'),
            'water_requirement'=> floatval($this->getPost('water_requirement')),
            'growth_stage'     => $this->getPost('growth_stage'),
        ];

        if (empty($data['crop_name']) || $data['area_planted'] <= 0) {
            $this->setFlash(ALERT_DANGER, 'Please fill all required fields.');
            $this->redirect('crop/add');
        }

        $cropModel->add($data);
        $this->setFlash(ALERT_SUCCESS, 'Crop added successfully!');
        $this->redirect('crop');
    }

    public function edit($id) {
        $this->requireFarmer();
        $this->generateCsrf();

        $farmerModel = $this->model('FarmerModel');
        $cropModel   = $this->model('CropModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $crop   = $cropModel->findById($id);

        if (!$crop || $crop['farmer_id'] != $farmer['id']) {
            $this->setFlash(ALERT_DANGER, 'Crop not found.');
            $this->redirect('crop');
        }

        $this->render('farmers.editcrop', [
            'title'  => 'Edit Crop',
            'crop'   => $crop,
            'farmer' => $farmer,
        ], 'main');
    }

    public function update($id) {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $cropModel   = $this->model('CropModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $crop   = $cropModel->findById($id);

        if (!$crop || $crop['farmer_id'] != $farmer['id']) {
            $this->redirect('crop');
        }

        $data = [
            'crop_name'         => htmlspecialchars($this->getPost('crop_name'), ENT_QUOTES, 'UTF-8'),
            'crop_type'         => $this->getPost('crop_type'),
            'area_planted'      => floatval($this->getPost('area_planted')),
            'planting_date'     => $this->getPost('planting_date'),
            'expected_harvest'  => $this->getPost('expected_harvest'),
            'water_requirement' => floatval($this->getPost('water_requirement')),
            'growth_stage'      => $this->getPost('growth_stage'),
            'status'            => $this->getPost('status'),
        ];

        $cropModel->update($id, $data);
        $this->setFlash(ALERT_SUCCESS, 'Crop updated successfully!');
        $this->redirect('crop');
    }

    public function delete($id) {
        $this->requireFarmer();

        $farmerModel = $this->model('FarmerModel');
        $cropModel   = $this->model('CropModel');
        $farmer = $farmerModel->getByUserId($_SESSION['user_id']);
        $crop   = $cropModel->findById($id);

        if ($crop && $crop['farmer_id'] == $farmer['id']) {
            $cropModel->delete($id);
            $this->setFlash(ALERT_SUCCESS, 'Crop removed.');
        }
        $this->redirect('crop');
    }
}
