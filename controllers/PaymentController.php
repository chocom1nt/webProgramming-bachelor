<?php
class PaymentController extends Controller {
    private $paymentTable;
    private $studentTable;
    private $courseTable;
    
    public function __construct() {
        require_once 'core/PaymentTable.php';
        require_once 'core/StudentTable.php';
        require_once 'core/CourseTable.php';
        $this->paymentTable = new PaymentTable();
        $this->studentTable = new StudentTable();
        $this->courseTable = new CourseTable();
    }
    
    public function index() {
        list($sort, $order) = $this->getSortParams();
        
        $payments = $this->paymentTable->getAll($sort, $order);
        $this->render('payments/list', [
            'payments' => $payments,
            'sort' => $sort,
            'order' => $order,
            'table' => 'payments'
        ]);
    }
    
    public function create() {
        if ($this->isPost()) {
            $this->paymentTable->create(
                $this->getInt('student_id'),
                $this->getInt('course_id'),
                $this->getFloat('amount'),
                $this->getString('payment_date'),
                $this->getString('status')
            );
            
            $this->redirect('index.php?table=payments');
        }
        
        $students = $this->studentTable->getForDropdown();
        $courses = $this->courseTable->getActiveCourses();
        
        $this->render('payments/form', [
            'students' => $students,
            'courses' => $courses,
            'payment' => null,
            'title' => 'Создание нового платежа'
        ]);
    }
    
    public function edit($id) {
        $payment = $this->paymentTable->getById($id);
        
        if (!$payment) {
            $this->redirect('index.php?table=payments');
            return;
        }
        
        if ($this->isPost()) {
            $this->paymentTable->update(
                $id,
                $this->getInt('student_id'),
                $this->getInt('course_id'),
                $this->getFloat('amount'),
                $this->getString('payment_date'),
                $this->getString('status')
            );
            
            $this->redirect('index.php?table=payments');
        }
        
        $students = $this->studentTable->getForDropdown();
        $courses = $this->courseTable->getActiveCourses();
        
        $this->render('payments/form', [
            'students' => $students,
            'courses' => $courses,
            'payment' => $payment,
            'title' => 'Редактирование платежа'
        ]);
    }
    
    public function delete($id) {
        $this->paymentTable->delete($id);
        $this->redirect('index.php?table=payments');
    }
}
?>