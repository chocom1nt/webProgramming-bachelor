<?php
session_start();
ob_start();

// Автозагрузка контроллеров
spl_autoload_register(function ($class) {
    $paths = [
        'controllers/' . $class . '.php',
        'core/' . $class . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Загружаем конфигурацию
if (file_exists('config/constants.php')) {
    require_once 'config/constants.php';
} else {
    // Устанавливаем константы по умолчанию
    define('BASE_URL', 'http://localhost:8080');
    define('IMAGE_NOT_FOUND', '/include/img/ImageNotFound.png');
    define('UPLOAD_DIR', '/uploads/');
}

// Загружаем Database вручную
require_once 'core/Database.php';

// Получаем параметры
$table = $_GET['table'] ?? '';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$current_table = $table;

// Подключаем header
if (file_exists('templates/header.php')) {
    include 'templates/header.php';
} else {
    // Если header.php не существует, показываем минимальный HTML
    echo '<!DOCTYPE html><html><head><title>Ошибка</title>';
    echo '<link rel="stylesheet" href="/css/bootstrap.min.css">';
    echo '<link rel="stylesheet" href="/css/styles.css">';
    echo '</head><body>';
    echo '<div class="container">';
}

// Роутинг - если table пустой, показываем dashboard
if (empty($table)) {
    // Показываем dashboard
    if (file_exists('views/dashboard.php')) {
        include 'views/dashboard.php';
    } else {
        echo '<div class="alert alert-warning">Файл dashboard.php не найден</div>';
        echo '<h1>Добро пожаловать в систему управления курсами!</h1>';
        echo '<p>Выберите раздел в меню навигации.</p>';
    }
    if (file_exists('templates/footer.html')) {
        include 'templates/footer.html';
    }
    exit;
}

// Если указана таблица, обрабатываем контроллер
switch ($table) {
    case 'courses':
        $controller = new CourseController();
        break;
    case 'teachers':
        $controller = new TeacherController();
        break;
    case 'teacher_types':
        $controller = new TeacherTypeController();
        break;
    case 'students':
        $controller = new StudentController();
        break;
    case 'payments':
        $controller = new PaymentController();
        break;
    default:
        // Если неизвестная таблица, показываем dashboard
        include 'views/dashboard.php';
        include 'templates/footer.html';
        exit;
}

// Выполняем действие
switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'edit':
        $controller->edit($id);
        break;
    case 'delete':
        $controller->delete($id);
        break;
    default:
        $controller->index();
}

// Подключаем footer
if (file_exists('templates/footer.html')) {
    include 'templates/footer.html';
} else {
    echo '</div></body></html>';
}
?>