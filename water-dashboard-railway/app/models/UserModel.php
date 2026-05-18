<?php
// ============================================================
// app/models/UserModel.php
// ============================================================

class UserModel extends Model {
    protected $table = 'users';

    // Authenticate user
    public function authenticate($email, $password) {
        $user = $this->db->fetch(
            "SELECT * FROM users WHERE email = ? AND is_active = 1",
            [$email]
        );
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Register new user
    public function register($data) {
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        return $this->db->insert(
            "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)",
            [$data['name'], $data['email'], $hashed, $data['role'] ?? 'farmer']
        );
    }

    // Check email exists
    public function emailExists($email) {
        return $this->db->fetchColumn(
            "SELECT COUNT(*) FROM users WHERE email = ?", [$email]
        ) > 0;
    }

    // Update last login
    public function updateLastLogin($id) {
        $this->db->execute(
            "UPDATE users SET last_login = NOW() WHERE id = ?", [$id]
        );
    }

    // Update profile
    public function updateProfile($id, $data) {
        return $this->db->execute(
            "UPDATE users SET name = ?, email = ? WHERE id = ?",
            [$data['name'], $data['email'], $id]
        );
    }

    // Change password
    public function changePassword($id, $newPassword) {
        $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->db->execute(
            "UPDATE users SET password = ? WHERE id = ?", [$hashed, $id]
        );
    }

    // Get all users with farmer count
    public function getAllWithStats() {
        return $this->db->fetchAll(
            "SELECT u.*, 
                    f.farm_name, f.location,
                    COUNT(wu.id) as usage_count
             FROM users u
             LEFT JOIN farmers f ON f.user_id = u.id
             LEFT JOIN water_usage wu ON wu.farmer_id = f.id
             GROUP BY u.id
             ORDER BY u.created_at DESC"
        );
    }

    // Toggle user active status
    public function toggleStatus($id) {
        return $this->db->execute(
            "UPDATE users SET is_active = IF(is_active=1, 0, 1) WHERE id = ?", [$id]
        );
    }
}
