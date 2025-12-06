<?php
require_once 'Database.php';

class StudentTable {
    private $conn;
    private $table = 'students';
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    // CREATE
    public function create($first_name, $last_name, $email, $phone = null) {
        $sql = "INSERT INTO {$this->table} (first_name, last_name, email, phone) 
                VALUES (:first_name, :last_name, :email, :phone)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone' => $phone
        ]);
    }
    
    // READ (all)
    public function getAll() {
        $sql = "SELECT * FROM {$this->table} ORDER BY last_name, first_name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // READ (one)
    public function getById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // UPDATE
    public function update($id, $first_name, $last_name, $email, $phone = null) {
        $sql = "UPDATE {$this->table} 
                SET first_name = :first_name, last_name = :last_name, 
                    email = :email, phone = :phone 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':phone' => $phone
        ]);
    }
    
    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Для выпадающего списка
    public function getForDropdown() {
        $sql = "SELECT id, CONCAT(first_name, ' ', last_name) as full_name 
                FROM {$this->table} 
                ORDER BY last_name";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}