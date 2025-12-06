<?php
class TeacherTypeController extends Controller {
    private $typeTable;
    
    public function __construct() {
        require_once 'core/TeacherTypeTable.php';
        $this->typeTable = new TeacherTypeTable();
    }
    
    public function index() {
        list($sort, $order) = $this->getSortParams();
        
        $types = $this->typeTable->getAll($sort, $order);
        $this->render('teacher-types/list', [
            'types' => $types,
            'sort' => $sort,
            'order' => $order,
            'table' => 'teacher-types'
        ]);
    }
    
    public function create() {
        if ($this->isPost()) {
            $this->typeTable->create(
                $this->getString('type_name'),
                $this->getString('description')
            );
            
            $this->redirect('index.php?table=teacher-types');
        }
        
        $this->render('teacher-types/form', [
            'type' => null,
            'title' => 'Создание нового типа преподавателя'
        ]);
    }
    
    public function edit($id) {
        $type = $this->typeTable->getById($id);
        
        if (!$type) {
            $this->redirect('index.php?table=teacher-types');
            return;
        }
        
        if ($this->isPost()) {
            $this->typeTable->update(
                $id,
                $this->getString('type_name'),
                $this->getString('description')
            );
            
            $this->redirect('index.php?table=teacher-types');
        }
        
        $this->render('teacher-types/form', [
            'type' => $type,
            'title' => 'Редактирование типа преподавателя'
        ]);
    }
    
    public function delete($id) {
        $this->typeTable->delete($id);
        $this->redirect('index.php?table=teacher-types');
    }
}
?>