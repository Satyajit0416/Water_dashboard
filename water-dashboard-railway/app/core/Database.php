<?php
class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        $port = defined('DB_PORT') ? DB_PORT : '3306';
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . $port . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() { return $this->connection; }

    public function query($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function fetchAll($sql, $params = []) { return $this->query($sql, $params)->fetchAll(); }
    public function fetch($sql, $params = []) { return $this->query($sql, $params)->fetch(); }
    public function fetchColumn($sql, $params = []) { return $this->query($sql, $params)->fetchColumn(); }

    public function insert($sql, $params = []) {
        $this->query($sql, $params);
        return $this->connection->lastInsertId();
    }

    public function execute($sql, $params = []) { return $this->query($sql, $params)->rowCount(); }
    public function beginTransaction() { return $this->connection->beginTransaction(); }
    public function commit() { return $this->connection->commit(); }
    public function rollback() { return $this->connection->rollBack(); }
}
