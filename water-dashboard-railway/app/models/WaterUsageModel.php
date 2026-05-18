<?php
// ============================================================
// app/models/WaterUsageModel.php
// ============================================================

class WaterUsageModel extends Model {
    protected $table = 'water_usage';

    // Get all usage with farmer and crop info
    public function getAllWithDetails($limit = null) {
        $sql = "SELECT wu.*, f.farm_name, u.name as farmer_name, c.crop_name, c.crop_type
                FROM water_usage wu
                JOIN farmers f ON f.id = wu.farmer_id
                JOIN users u ON u.id = f.user_id
                LEFT JOIN crops c ON c.id = wu.crop_id
                ORDER BY wu.usage_date DESC, wu.id DESC";
        if ($limit) $sql .= " LIMIT {$limit}";
        return $this->db->fetchAll($sql);
    }

    // Get farmer's usage with details
    public function getByFarmer($farmerId, $limit = null) {
        $sql = "SELECT wu.*, c.crop_name, c.crop_type
                FROM water_usage wu
                LEFT JOIN crops c ON c.id = wu.crop_id
                WHERE wu.farmer_id = ?
                ORDER BY wu.usage_date DESC, wu.id DESC";
        if ($limit) $sql .= " LIMIT {$limit}";
        return $this->db->fetchAll($sql, [$farmerId]);
    }

    // Add water usage record
    public function add($data) {
        return $this->db->insert(
            "INSERT INTO water_usage (farmer_id, crop_id, usage_date, amount_used, irrigation_method, duration_minutes, pump_power, area_irrigated, notes)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            [
                $data['farmer_id'], $data['crop_id'] ?: null, $data['usage_date'],
                $data['amount_used'], $data['irrigation_method'], $data['duration_minutes'],
                $data['pump_power'] ?: null, $data['area_irrigated'] ?: null, $data['notes'] ?: null
            ]
        );
    }

    // Update usage record
    public function update($id, $data) {
        return $this->db->execute(
            "UPDATE water_usage SET crop_id=?, usage_date=?, amount_used=?, irrigation_method=?, 
             duration_minutes=?, pump_power=?, area_irrigated=?, notes=? WHERE id=?",
            [
                $data['crop_id'] ?: null, $data['usage_date'], $data['amount_used'],
                $data['irrigation_method'], $data['duration_minutes'],
                $data['pump_power'] ?: null, $data['area_irrigated'] ?: null, $data['notes'] ?: null, $id
            ]
        );
    }

    // Get total usage system-wide
    public function getTotalUsage() {
        return $this->db->fetchColumn("SELECT COALESCE(SUM(amount_used), 0) FROM water_usage");
    }

    // Get this month's usage
    public function getMonthUsage($farmerId = null) {
        $sql = "SELECT COALESCE(SUM(amount_used), 0) FROM water_usage 
                WHERE MONTH(usage_date) = MONTH(CURDATE()) AND YEAR(usage_date) = YEAR(CURDATE())";
        $params = [];
        if ($farmerId) {
            $sql .= " AND farmer_id = ?";
            $params[] = $farmerId;
        }
        return $this->db->fetchColumn($sql, $params);
    }

    // Get today's usage
    public function getTodayUsage($farmerId = null) {
        $sql = "SELECT COALESCE(SUM(amount_used), 0) FROM water_usage WHERE usage_date = CURDATE()";
        $params = [];
        if ($farmerId) {
            $sql .= " AND farmer_id = ?";
            $params[] = $farmerId;
        }
        return $this->db->fetchColumn($sql, $params);
    }

    // Get crop-wise usage for chart
    public function getCropWiseUsage($farmerId = null) {
        $sql = "SELECT c.crop_name, c.crop_type, SUM(wu.amount_used) as total
                FROM water_usage wu
                JOIN crops c ON c.id = wu.crop_id
                WHERE wu.crop_id IS NOT NULL";
        $params = [];
        if ($farmerId) {
            $sql .= " AND wu.farmer_id = ?";
            $params[] = $farmerId;
        }
        $sql .= " GROUP BY wu.crop_id ORDER BY total DESC LIMIT 10";
        return $this->db->fetchAll($sql, $params);
    }

    // Get method-wise usage
    public function getMethodWiseUsage($farmerId = null) {
        $sql = "SELECT irrigation_method, SUM(amount_used) as total, COUNT(*) as count
                FROM water_usage";
        $params = [];
        if ($farmerId) {
            $sql .= " WHERE farmer_id = ?";
            $params[] = $farmerId;
        }
        $sql .= " GROUP BY irrigation_method";
        return $this->db->fetchAll($sql, $params);
    }

    // Get monthly global usage (for admin chart)
    public function getGlobalMonthlyUsage($months = 6) {
        return $this->db->fetchAll(
            "SELECT DATE_FORMAT(usage_date, '%Y-%m') as month,
                    DATE_FORMAT(usage_date, '%b %Y') as month_label,
                    SUM(amount_used) as total,
                    COUNT(*) as records
             FROM water_usage
             WHERE usage_date >= DATE_SUB(CURDATE(), INTERVAL ? MONTH)
             GROUP BY DATE_FORMAT(usage_date, '%Y-%m')
             ORDER BY month ASC",
            [$months]
        );
    }

    // Search usage records
    public function search($farmerId, $filters = []) {
        $sql = "SELECT wu.*, c.crop_name FROM water_usage wu
                LEFT JOIN crops c ON c.id = wu.crop_id
                WHERE wu.farmer_id = ?";
        $params = [$farmerId];

        if (!empty($filters['date_from'])) {
            $sql .= " AND wu.usage_date >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND wu.usage_date <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['method'])) {
            $sql .= " AND wu.irrigation_method = ?";
            $params[] = $filters['method'];
        }
        $sql .= " ORDER BY wu.usage_date DESC";
        return $this->db->fetchAll($sql, $params);
    }
}
