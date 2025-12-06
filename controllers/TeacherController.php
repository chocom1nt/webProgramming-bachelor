<?php
class TeacherController extends Controller {
    private $teacherTable;
    private $typeTable;
    
    public function __construct() {
        require_once 'core/TeacherTable.php';
        require_once 'core/TeacherTypeTable.php';
        $this->teacherTable = new TeacherTable();
        $this->typeTable = new TeacherTypeTable();
    }
    
    public function index() {
        list($sort, $order) = $this->getSortParams();
        
        $teachers = $this->teacherTable->getAll($sort, $order);
        $this->render('teachers/list', [
            'teachers' => $teachers,
            'sort' => $sort,
            'order' => $order,
            'table' => 'teachers'
        ]);
    }
    
    public function create() {
        if ($this->isPost()) {
            $this->teacherTable->create(
                $this->getString('first_name'),
                $this->getString('last_name'),
                $this->getString('email'),
                $this->getInt('teacher_type_id')
            );
            
            $this->redirect('index.php?table=teachers');
        }
        
        $types = $this->typeTable->getAll();
        $this->render('teachers/form', [
            'types' => $types,
            'teacher' => null,
            'title' => 'Создание нового преподавателя'
        ]);
    }
    
    public function edit($id) {
        $teacher = $this->teacherTable->getById($id);
        
        if (!$teacher) {
            $this->redirect('index.php?table=teachers');
            return;
        }
        
        if ($this->isPost()) {
            $this->teacherTable->update(
                $id,
                $this->getString('first_name'),
                $this->getString('last_name'),
                $this->getString('email'),
                $this->getInt('teacher_type_id')
            );
            
            $this->redirect('index.php?table=teachers');
        }
        
        $types = $this->typeTable->getAll();
        $this->render('teachers/form', [
            'types' => $types,
            'teacher' => $teacher,
            'title' => 'Редактирование преподавателя'
        ]);
    }
    
    public function delete($id) {
        $this->teacherTable->delete($id);
        $this->redirect('index.php?table=teachers');
    }
}
?>