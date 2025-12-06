<?php
require_once 'Database.php';

class CourseTable {
    private $conn;
    private $table = 'courses';
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    public function create($title, $image_url, $image_alt, $teacher_id, $program, $price, $is_active = true) {
        $sql = "INSERT INTO {$this->table} (title, image_url, image_alt, teacher_id, program, price, is_active) 
                VALUES (:title, :image_url, :image_alt, :teacher_id, :program, :price, :is_active)";
        $stmt = $this->conn->prepare($sql);
        
        // Используем PDO::PARAM_BOOL для boolean значения
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':image_url', $image_url);
        $stmt->bindValue(':image_alt', $image_alt);
        $stmt->bindValue(':teacher_id', $teacher_id, PDO::PARAM_INT);
        $stmt->bindValue(':program', $program);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':is_active', $is_active, PDO::PARAM_BOOL);
        
        return $stmt->execute();
    }
    
    public function getAll($sort = 'id', $order = 'asc') {
        $allowedSorts = ['id', 'title', 'price', 'is_active', 'teacher_name', 'teacher_type'];
        $allowedOrders = ['asc', 'desc'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }
        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }
        
        $sql = "SELECT c.*, 
                       CONCAT(t.first_name, ' ', t.last_name) as teacher_name,
                       tt.type_name as teacher_type
                FROM {$this->table} c
                LEFT JOIN teachers t ON c.teacher_id = t.id
                LEFT JOIN teacher_types tt ON t.teacher_type_id = tt.id";
        
        if (in_array($sort, ['teacher_name', 'teacher_type'])) {
            $sql .= " ORDER BY $sort $order";
        } else {
            $sql .= " ORDER BY c.$sort $order";
        }
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT c.*, 
                       CONCAT(t.first_name, ' ', t.last_name) as teacher_name,
                       t.email as teacher_email,
                       tt.type_name as teacher_type
                FROM {$this->table} c
                LEFT JOIN teachers t ON c.teacher_id = t.id
                LEFT JOIN teacher_types tt ON t.teacher_type_id = tt.id
                WHERE c.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $title, $image_url, $image_alt, $teacher_id, $program, $price, $is_active) {
        $sql = "UPDATE {$this->table} 
                SET title = :title, image_url = :image_url, image_alt = :image_alt,
                    teacher_id = :teacher_id, program = :program, price = :price,
                    is_active = :is_active
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':title', $title);
        $stmt->bindValue(':image_url', $image_url);
        $stmt->bindValue(':image_alt', $image_alt);
        $stmt->bindValue(':teacher_id', $teacher_id, PDO::PARAM_INT);
        $stmt->bindValue(':program', $program);
        $stmt->bindValue(':price', $price);
        $stmt->bindValue(':is_active', $is_active, PDO::PARAM_BOOL);
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    public function getActiveCourses() {
        // PostgreSQL требует true/false для boolean полей
        $sql = "SELECT c.*, 
                       CONCAT(t.first_name, ' ', t.last_name) as teacher_name
                FROM {$this->table} c
                LEFT JOIN teachers t ON c.teacher_id = t.id
                WHERE c.is_active = true
                ORDER BY c.title";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>