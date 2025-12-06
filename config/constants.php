<?php
// Базовые настройки
define('BASE_URL', 'http://localhost:8080');
define('BASE_PATH', '/');

// Пути к ресурсам
define('IMAGE_NOT_FOUND', BASE_PATH . 'include/img/ImageNotFound.png');
define('CSS_PATH', BASE_PATH . 'css/');
define('JS_PATH', BASE_PATH . 'js/');

// Настройки базы данных
define('DB_HOST', 'db');
define('DB_NAME', 'courses_db');
define('DB_USER', 'user');
define('DB_PASS', 'password');

// Креденшиалы по умолчанию
define('DEFAULT_USERNAME', 'admin');
define('DEFAULT_PASSWORD', 'admin');

// Отладочный режим
define('DEBUG_MODE', true);
?>