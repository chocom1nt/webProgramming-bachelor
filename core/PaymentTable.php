<?php
require_once 'Database.php';

class PaymentTable {
    private $conn;
    private $table = 'payments';
    
    public function __construct() {
        $this->conn = Database::getInstance();
    }
    
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
    
    public function getAll($sort = 'id', $order = 'asc') {
        $allowedSorts = ['id', 'amount', 'payment_date', 'status', 'student_name', 'course_title'];
        $allowedOrders = ['asc', 'desc'];
        
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }
        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }
        
        $sql = "SELECT p.*, 
                       CONCAT(s.first_name, ' ', s.last_name) as student_name,
                       c.title as course_title,
                       c.price as course_price
                FROM {$this->table} p
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN courses c ON p.course_id = c.id";
        
        if (in_array($sort, ['student_name', 'course_title'])) {
            $sql .= " ORDER BY $sort $order";
        } else {
            $sql .= " ORDER BY p.$sort $order";
        }
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
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
    
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
    
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
?>