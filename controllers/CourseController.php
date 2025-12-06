<?php
class CourseController extends Controller {
    private $courseTable;
    private $teacherTable;
    
    public function __construct() {
        require_once 'core/CourseTable.php';
        require_once 'core/TeacherTable.php';
        $this->courseTable = new CourseTable();
        $this->teacherTable = new TeacherTable();
    }
    
    public function index() {
        list($sort, $order) = $this->getSortParams();
        
        $courses = $this->courseTable->getAll($sort, $order);
        $this->render('courses/list', [
            'courses' => $courses,
            'sort' => $sort,
            'order' => $order,
            'table' => 'courses'
        ]);
    }
    
    public function create() {
        if ($this->isPost()) {
            // Преобразуем в boolean для PostgreSQL
            $is_active = isset($_POST['is_active']) && $_POST['is_active'] == 'on';
            
            $this->courseTable->create(
                $this->getString('title'),
                $this->getString('image_url'),
                $this->getString('image_alt'),
                $this->getInt('teacher_id'),
                $this->getString('program'),
                $this->getFloat('price'),
                $is_active
            );
            
            $this->redirect(BASE_PATH . '?table=courses');
        }
        
        $teachers = $this->teacherTable->getForDropdown();
        $this->render('courses/form', [
            'teachers' => $teachers,
            'course' => null,
            'title' => 'Создание нового курса'
        ]);
    }
    
        public function edit($id) {
        $course = $this->courseTable->getById($id);
        
        if (!$course) {
            $this->redirect('/?table=courses');
            return;
        }
        
        if ($this->isPost()) {
            $image_url = $this->processImageUpload($course['image_url']);
            $is_active = isset($_POST['is_active']) ? 1 : 0;
            
            $this->courseTable->update(
                $id,
                $this->getString('title'),
                $image_url,
                $this->getString('image_alt'),
                $this->getInt('teacher_id'),
                $this->getString('program'),
                $this->getFloat('price'),
                $is_active
            );
            
            $this->redirect('/?table=courses');
        }
        
        $teachers = $this->teacherTable->getForDropdown();
        $this->render('courses/form', [
            'teachers' => $teachers,
            'course' => $course,
            'title' => 'Редактирование курса'
        ]);
    }
     
    private function processImageUpload($current_image = null) {
        // Проверяем, было ли загружено новое изображение
        if (!empty($_POST['base64_image']) && strpos($_POST['base64_image'], 'data:image') === 0) {
            $base64_image = $_POST['base64_image'];
            
            // Проверяем размер base64 строки
            if (strlen($base64_image) > 1000000) { // Примерно 1MB в base64
                return $current_image; // Возвращаем старое изображение
            }
            
            // Если base64 не слишком длинный, сохраняем как есть
            return $base64_image;
        }
        
        // Если URL был введен вручную
        if (!empty($_POST['image_url'])) {
            return $_POST['image_url'];
        }
        
        // Если ничего не изменилось, возвращаем текущее изображение
        return $current_image;
    }
    public function delete($id) {
        $this->courseTable->delete($id);
        $this->redirect(BASE_PATH . '?table=courses');
    }

}

?>

