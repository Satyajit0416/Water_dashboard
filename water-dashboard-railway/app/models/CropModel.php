<?php
// ============================================================
// app/models/CropModel.php
// ============================================================

class CropModel extends Model {
    protected $table = 'crops';

    // Get crops for a farmer
    public function getByFarmer($farmerId) {
        return $this->db->fetchAll(
            "SELECT c.*, COALESCE(SUM(wu.amount_used), 0) as total_water_used
             FROM crops c
             LEFT JOIN water_usage wu ON wu.crop_id = c.id
             WHERE c.farmer_id = ?
             GROUP BY c.id
             ORDER BY c.status ASC, c.planting_date DESC",
            [$farmerId]
        );
    }

    // Add crop
    public function add($data) {
        return $this->db->insert(
            "INSERT INTO crops (farmer_id, crop_name, crop_type, area_planted, planting_date, expected_harvest, water_requirement, growth_stage)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['farmer_id'], $data['crop_name'], $data['crop_type'],
                $data['area_planted'], $data['planting_date'], $data['expected_harvest'],
                $data['water_requirement'], $data['growth_stage'] ?? 'seedling'
            ]
        );
    }

    // Update crop
    public function update($id, $data) {
        return $this->db->execute(
            "UPDATE crops SET crop_name=?, crop_type=?, area_planted=?, planting_date=?, 
             expected_harvest=?, water_requirement=?, growth_stage=?, status=? WHERE id=?",
            [
                $data['crop_name'], $data['crop_type'], $data['area_planted'],
                $data['planting_date'], $data['expected_harvest'], $data['water_requirement'],
                $data['growth_stage'], $data['status'], $id
            ]
        );
    }

    // Get active crops for dropdown
    public function getActiveCrops($farmerId) {
        return $this->db->fetchAll(
            "SELECT id, crop_name, crop_type, area_planted FROM crops 
             WHERE farmer_id = ? AND status = 'active' ORDER BY crop_name ASC",
            [$farmerId]
        );
    }

    // Get total active crops count
    public function getTotalActive() {
        return $this->db->fetchColumn(
            "SELECT COUNT(*) FROM crops WHERE status = 'active'"
        );
    }
}
