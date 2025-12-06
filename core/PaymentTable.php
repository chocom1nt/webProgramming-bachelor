<?php
require_once 'Database.php';

class PaymentTable {
    private $conn;
    private $table = 'payments';
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
    // CREATE
    public function create($student_id, $course_id, $amount, $payment_date, $status = 'pending') {
        $sql = "INSERT INTO {$this->table} (student_id, course_id, amount, payment_date, status) 
                VALUES (:student_id, :course_id, :amount, :payment_date, :status)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':student_id' => $student_id,
            ':course_id' => $course_id,
            ':amount' => $amount,
            ':payment_date' => $payment_date,
            ':status' => $status
        ]);
    }
    
    // READ (all with joins)
    public function getAll() {
        $sql = "SELECT p.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       c.title as course_title,
                       c.price as course_price
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN courses c ON p.course_id = c.id
                ORDER BY p.payment_date DESC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // READ (one)
    public function getById($id) {
        $sql = "SELECT p.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       s.email as student_email,
                       c.title as course_title,
                       c.price as course_price
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN courses c ON p.course_id = c.id
                WHERE p.id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // UPDATE
    public function update($id, $student_id, $course_id, $amount, $payment_date, $status) {
        $sql = "UPDATE {$this->table} 
                SET student_id = :student_id, course_id = :course_id, 
                    amount = :amount, payment_date = :payment_date, status = :status
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':student_id' => $student_id,
            ':course_id' => $course_id,
            ':amount' => $amount,
            ':payment_date' => $payment_date,
            ':status' => $status
        ]);
    }
    
    // DELETE
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
    // Получить платежи по студенту
    public function getByStudent($student_id) {
        $sql = "SELECT p.*, c.title as course_title
                FROM {$this->table} p
                LEFT JOIN courses c ON p.course_id = c.id
                WHERE p.student_id = :student_id
                ORDER BY p.payment_date DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}