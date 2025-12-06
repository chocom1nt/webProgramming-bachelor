<?php
class Controller {
    protected function render($view, $data = []) {
        extract($data);
        $viewPath = "views/{$view}.php";
        
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "<div class='alert alert-danger'>Представление не найдено: {$view}</div>";
        }
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }
    
    protected function getPost($key, $default = null) {
        return $_POST[$key] ?? $default;
    }
    
    protected function getInt($key, $default = 0) {
        return isset($_POST[$key]) ? (int)$_POST[$key] : $default;
    }
    
    protected function getFloat($key, $default = 0.0) {
        return isset($_POST[$key]) ? (float)$_POST[$key] : $default;
    }
    
    protected function getString($key, $default = '') {
        return isset($_POST[$key]) ? trim($_POST[$key]) : $default;
    }
    
    protected function getSortParams() {
        $sort = $_GET['sort'] ?? 'id';
        $order = $_GET['order'] ?? 'asc';
        
        $allowedOrders = ['asc', 'desc'];
        if (!in_array($order, $allowedOrders)) {
            $order = 'asc';
        }
        
        return [$sort, $order];
    }
    
    protected function getSortLink($field, $currentSort, $currentOrder, $table) {
        $order = ($currentSort == $field && $currentOrder == 'asc') ? 'desc' : 'asc';
        return "index.php?table={$table}&sort={$field}&order={$order}";
    }
    
    protected function getSortIcon($field, $currentSort, $currentOrder) {
        if ($currentSort == $field) {
            return $currentOrder == 'asc' ? '↑' : '↓';
        }
        return '';
    }
}
?>