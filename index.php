<?php
session_start();
ob_start();

// Загружаем конфигурацию
require_once 'config/constants.php';

// Проверка аутентификации
function checkAuth() {
    return isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true;
}

// Обработка входа
if (isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === DEFAULT_USERNAME && $password === DEFAULT_PASSWORD) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
        header('Location: ' . BASE_PATH);
        exit;
    } else {
        $login_error = 'Неверный логин или пароль';
    }
}

// Обработка выхода
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ' . BASE_PATH);
    exit;
}

// Если не авторизован, показываем форму входа
if (!checkAuth()) {
    // Устанавливаем заголовок для предотвращения кэширования
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    
    // Подключаем форму входа
    include 'templates/login.html';
    exit;
}

// Автозагрузка классов
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

// Загружаем Database
require_once 'core/Database.php';

$table = $_GET['table'] ?? 'courses';
$action = $_GET['action'] ?? 'list';
$id = $_GET['id'] ?? null;

$current_table = $table;

// Подключаем header для авторизованных пользователей
include 'templates/header.html';

// Роутинг для авторизованных пользователей
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
        // Показываем dashboard
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
include 'templates/footer.html';
?>