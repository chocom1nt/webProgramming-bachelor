<?php
class StudentController extends Controller {
    private $studentTable;
    
    public function __construct() {
        require_once 'core/StudentTable.php';
        $this->studentTable = new StudentTable();
    }
    
    public function index() {
        list($sort, $order) = $this->getSortParams();
        
        $students = $this->studentTable->getAll($sort, $order);
        $this->render('students/list', [
            'students' => $students,
            'sort' => $sort,
            'order' => $order,
            'table' => 'students'
        ]);
    }
    
    public function create() {
        if ($this->isPost()) {
            $phone = $this->getString('phone');
            
            if ($phone && !preg_match('/^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
                $_SESSION['error'] = 'Номер телефона должен быть в формате: +7 (XXX)-XXX-XX-XX';
                $this->redirect('index.php?table=students&action=create');
                return;
            }
            
            $this->studentTable->create(
                $this->getString('first_name'),
                $this->getString('last_name'),
                $this->getString('email'),
                $phone
            );
            
            $this->redirect('index.php?table=students');
        }
        
        $error = $_SESSION['error'] ?? null;
        if ($error) {
            unset($_SESSION['error']);
        }
        
        $this->render('students/form', [
            'student' => null,
            'title' => 'Создание нового студента',
            'error' => $error
        ]);
    }
    
    public function edit($id) {
        $student = $this->studentTable->getById($id);
        
        if (!$student) {
            $this->redirect('index.php?table=students');
            return;
        }
        
        if ($this->isPost()) {
            $phone = $this->getString('phone');
            
            if ($phone && !preg_match('/^\+7 \(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phone)) {
                $_SESSION['error'] = 'Номер телефона должен быть в формате: +7 (XXX)-XXX-XX-XX';
                $this->redirect('index.php?table=students&action=edit&id=' . $id);
                return;
            }
            
            $this->studentTable->update(
                $id,
                $this->getString('first_name'),
                $this->getString('last_name'),
                $this->getString('email'),
                $phone
            );
            
            $this->redirect('index.php?table=students');
        }
        
        $error = $_SESSION['error'] ?? null;
        if ($error) {
            unset($_SESSION['error']);
        }
        
        $this->render('students/form', [
            'student' => $student,
            'title' => 'Редактирование студента',
            'error' => $error
        ]);
    }
    
    public function delete($id) {
        $this->studentTable->delete($id);
        $this->redirect('index.php?table=students');
    }
}
?>