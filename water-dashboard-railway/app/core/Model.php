<?php
// ============================================================
// app/core/Model.php - Base Model Class
// ============================================================

class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // Find all records
    public function findAll($orderBy = 'id', $order = 'DESC', $limit = null) {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$orderBy} {$order}";
        if ($limit) $sql .= " LIMIT {$limit}";
        return $this->db->fetchAll($sql);
    }

    // Find by ID
    public function findById($id) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    // Find by column value
    public function findBy($column, $value) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$column} = ?",
            [$value]
        );
    }

    // Find multiple by column value
    public function findAllBy($column, $value, $orderBy = 'id', $order = 'DESC') {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$column} = ? ORDER BY {$orderBy} {$order}",
            [$value]
        );
    }

    // Count records
    public function count($where = '', $params = []) {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if ($where) $sql .= " WHERE {$where}";
        return $this->db->fetchColumn($sql, $params);
    }

    // Delete by ID
    public function delete($id) {
        return $this->db->execute(
            "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    // Paginate results
    public function paginate($page = 1, $perPage = RECORDS_PER_PAGE, $where = '', $params = []) {
        $offset = ($page - 1) * $perPage;
        $sql = "SELECT * FROM {$this->table}";
        if ($where) $sql .= " WHERE {$where}";
        $sql .= " ORDER BY {$this->primaryKey} DESC LIMIT {$perPage} OFFSET {$offset}";
        return $this->db->fetchAll($sql, $params);
    }

    // Sanitize input
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}
