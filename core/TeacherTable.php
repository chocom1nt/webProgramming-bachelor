<?php
require_once 'Database.php';

class TeacherTable {
    private $conn;
    private $table = 'teachers';
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    public function create($first_name, $last_name, $email, $teacher_type_id) {
        $sql = "INSERT INTO {$this->table} (first_name, last_name, email, teacher_type_id) 
                VALUES (:first_name, :last_name, :email, :teacher_type_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':teacher_type_id' => $teacher_type_id
        ]);
    }
    
    public function getAll($sort = 'id', $order = 'asc') {
        $allowedSorts = ['id', 'first_name', 'last_name', 'email', 'type_name'];
        $allowedOrders = ['asc', 'desc'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }
        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }
        
        $sql = "SELECT t.*, tt.type_name 
                FROM {$this->table} t 
                LEFT JOIN teacher_types tt ON t.teacher_type_id = tt.id";
        
        if ($sort == 'type_name') {
            $sql .= " ORDER BY tt.type_name $order";
        } else {
            $sql .= " ORDER BY t.$sort $order";
        }
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT t.*, tt.type_name 
                FROM {$this->table} t 
                LEFT JOIN teacher_types tt ON t.teacher_type_id = tt.id 
                WHERE t.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $first_name, $last_name, $email, $teacher_type_id) {
        $sql = "UPDATE {$this->table} 
                SET first_name = :first_name, last_name = :last_name, 
                    email = :email, teacher_type_id = :teacher_type_id 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':teacher_type_id' => $teacher_type_id
        ]);
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getForDropdown() {
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as full_name 
                FROM {$this->table} 
                ORDER BY last_name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>