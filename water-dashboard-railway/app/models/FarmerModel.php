<?php
// ============================================================
// app/models/FarmerModel.php
// ============================================================

class FarmerModel extends Model {
    protected $table = 'farmers';

    // Get farmer by user ID
    public function getByUserId($userId) {
        return $this->db->fetch(
            "SELECT f.*, u.name, u.email, u.role, u.last_login
             FROM farmers f
             JOIN users u ON u.id = f.user_id
             WHERE f.user_id = ?",
            [$userId]
        );
    }

    // Get all farmers with user info and stats
    public function getAllWithStats() {
        return $this->db->fetchAll(
            "SELECT f.*, u.name, u.email, u.is_active,
                    COUNT(DISTINCT wu.id) as total_records,
                    COALESCE(SUM(wu.amount_used), 0) as total_usage,
                    COUNT(DISTINCT c.id) as crop_count
             FROM farmers f
             JOIN users u ON u.id = f.user_id
             LEFT JOIN water_usage wu ON wu.farmer_id = f.id
             LEFT JOIN crops c ON c.farmer_id = f.id
             GROUP BY f.id
             ORDER BY f.created_at DESC"
        );
    }

    // Create farmer profile
    public function create($data) {
        return $this->db->insert(
            "INSERT INTO farmers (user_id, farm_name, location, farm_size, soil_type, water_source, phone) 
             VALUES (?, ?, ?, ?, ?, ?, ?)",
            [
                $data['user_id'], $data['farm_name'], $data['location'],
                $data['farm_size'], $data['soil_type'], $data['water_source'], $data['phone']
            ]
        );
    }

    // Update farmer profile
    public function update($id, $data) {
        return $this->db->execute(
            "UPDATE farmers SET farm_name=?, location=?, farm_size=?, soil_type=?, water_source=?, phone=? WHERE id=?",
            [
                $data['farm_name'], $data['location'], $data['farm_size'],
                $data['soil_type'], $data['water_source'], $data['phone'], $id
            ]
        );
    }

    // Get farmer stats summary
    public function getStats($farmerId) {
        return $this->db->fetch(
            "SELECT 
                COUNT(DISTINCT wu.id) as total_records,
                COALESCE(SUM(wu.amount_used), 0) as total_usage,
                COALESCE(AVG(wu.amount_used), 0) as avg_usage,
                COUNT(DISTINCT c.id) as crop_count,
                COUNT(DISTINCT irs.id) as scheduled_count
             FROM farmers f
             LEFT JOIN water_usage wu ON wu.farmer_id = f.id
             LEFT JOIN crops c ON c.farmer_id = f.id AND c.status = 'active'
             LEFT JOIN irrigation_schedule irs ON irs.farmer_id = f.id AND irs.status = 'pending'
             WHERE f.id = ?",
            [$farmerId]
        );
    }

    // Get monthly usage for farmer
    public function getMonthlyUsage($farmerId, $months = 6) {
        return $this->db->fetchAll(
            "SELECT DATE_FORMAT(usage_date, '%Y-%m') as month,
                    DATE_FORMAT(usage_date, '%b %Y') as month_label,
                    SUM(amount_used) as total
             FROM water_usage
             WHERE farmer_id = ? AND usage_date >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
             GROUP BY DATE_FORMAT(usage_date, '%Y-%m')
             ORDER BY month ASC",
            [$farmerId, $months]
        );
    }

    // Get daily usage (last N days)
    public function getDailyUsage($farmerId, $days = 30) {
        return $this->db->fetchAll(
            "SELECT usage_date, SUM(amount_used) as total
             FROM water_usage
             WHERE farmer_id = ? AND usage_date >= DATE_SUB(CURDATE(), INTERVAL ? DAY)
             GROUP BY usage_date
             ORDER BY usage_date ASC",
            [$farmerId, $days]
        );
    }

    // Total count
    public function getTotalCount() {
        return $this->db->fetchColumn("SELECT COUNT(*) FROM farmers");
    }
}
