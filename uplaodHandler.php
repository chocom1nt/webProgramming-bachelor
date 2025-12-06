<?php
session_start();
require_once 'core/Database.php';

// Настройки
$max_file_size = 5 * 1024 * 1024; // 5MB
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
$upload_dir = __DIR__ . '/uploads/';

// Создаем папку если не существует
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$response = ['success' => false, 'message' => '', 'url' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $response['message'] = 'Ошибка загрузки файла';
        echo json_encode($response);
        exit;
    }
    
    $file = $_FILES['image'];
    
    // Проверка размера
    if ($file['size'] > $max_file_size) {
        $response['message'] = 'Файл слишком большой. Максимальный размер: 5MB';
        echo json_encode($response);
        exit;
    }
    
    // Проверка типа файла
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        $response['message'] = 'Недопустимый тип файла. Разрешены только изображения (JPEG, PNG, GIF, WebP)';
        echo json_encode($response);
        exit;
    }
    
    // Генерация уникального имени файла
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . date('Ymd_His') . '.' . $extension;
    $destination = $upload_dir . $filename;
    
    // Перемещение файла
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        // Установка правильных прав
        chmod($destination, 0644);
        
        $response['success'] = true;
        $response['message'] = 'Файл успешно загружен';
        $response['url'] = '/uploads/' . $filename;
        $response['filename'] = $filename;
    } else {
        $response['message'] = 'Ошибка при сохранении файла';
    }
} else {
    $response['message'] = 'Неверный метод запроса';
}

echo json_encode($response);
?>