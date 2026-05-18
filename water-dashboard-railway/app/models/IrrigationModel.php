<?php
// ============================================================
// app/models/IrrigationModel.php
// ============================================================

class IrrigationModel extends Model {
    protected $table = 'irrigation_schedule';

    // Get schedule for farmer with crop details
    public function getByFarmer($farmerId, $upcoming = false) {
        $sql = "SELECT irs.*, c.crop_name, c.crop_type
                FROM irrigation_schedule irs
                LEFT JOIN crops c ON c.id = irs.crop_id
                WHERE irs.farmer_id = ?";
        if ($upcoming) {
            $sql .= " AND irs.scheduled_date >= CURDATE() AND irs.status = 'pending'";
        }
        $sql .= " ORDER BY irs.scheduled_date ASC, irs.scheduled_time ASC";
        return $this->db->fetchAll($sql, [$farmerId]);
    }

    // Add schedule
    public function add($data) {
        return $this->db->insert(
            "INSERT INTO irrigation_schedule (farmer_id, crop_id, scheduled_date, scheduled_time, duration_minutes, irrigation_method, estimated_water, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['farmer_id'], $data['crop_id'] ?: null, $data['scheduled_date'],
                $data['scheduled_time'], $data['duration_minutes'], $data['irrigation_method'],
                $data['estimated_water'], $data['notes'] ?: null
            ]
        );
    }

    // Update schedule status
    public function updateStatus($id, $status) {
        return $this->db->execute(
            "UPDATE irrigation_schedule SET status = ? WHERE id = ?", [$status, $id]
        );
    }

    // Delete schedule
    public function deleteSchedule($id, $farmerId) {
        return $this->db->execute(
            "DELETE FROM irrigation_schedule WHERE id = ? AND farmer_id = ?", [$id, $farmerId]
        );
    }

    // Get upcoming schedules for all farmers (admin)
    public function getUpcomingAll($limit = 10) {
        return $this->db->fetchAll(
            "SELECT irs.*, c.crop_name, f.farm_name, u.name as farmer_name
             FROM irrigation_schedule irs
             LEFT JOIN crops c ON c.id = irs.crop_id
             JOIN farmers f ON f.id = irs.farmer_id
             JOIN users u ON u.id = f.user_id
             WHERE irs.scheduled_date >= CURDATE() AND irs.status = 'pending'
             ORDER BY irs.scheduled_date ASC, irs.scheduled_time ASC
             LIMIT ?",
            [$limit]
        );
    }

    // Count pending for farmer
    public function countPending($farmerId) {
        return $this->db->fetchColumn(
            "SELECT COUNT(*) FROM irrigation_schedule WHERE farmer_id = ? AND status = 'pending' AND scheduled_date >= CURDATE()",
            [$farmerId]
        );
    }
}
