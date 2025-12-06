<?php
require_once 'Database.php';

class TeacherTypeTable {
    private $conn;
    private $table = 'teacher_types';
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    public function create($type_name, $description = null) {
        $sql = "INSERT INTO {$this->table} (type_name, description) VALUES (:type_name, :description)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':type_name' => $type_name,
            ':description' => $description
        ]);
    }
    
    public function getAll($sort = 'id', $order = 'asc') {
        $allowedSorts = ['id', 'type_name'];
        $allowedOrders = ['asc', 'desc'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }
        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }
        
        $sql = "SELECT * FROM {$this->table} ORDER BY $sort $order";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $type_name, $description = null) {
        $sql = "UPDATE {$this->table} SET type_name = :type_name, description = :description WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':type_name' => $type_name,
            ':description' => $description
        ]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
?>