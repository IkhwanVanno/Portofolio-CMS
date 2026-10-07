<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ikhwanva_porto');
define('BASE_URL', '');
define('UPLOAD_PATH', __DIR__ . '/../uploads/');
define('UPLOAD_URL', 'uploads/');

session_start();

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]
            );
        } catch (PDOException $e) {
            die(json_encode(['error' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }
    return $pdo;
}

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function uploadImage($file, $folder = '') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return '';
    
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $allowed)) return '';
    
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '_' . time() . '.' . $ext;
    $subdir = $folder ? $folder . '/' : '';
    $dest = UPLOAD_PATH . $subdir . $filename;
    
    if (!is_dir(UPLOAD_PATH . $subdir)) {
        mkdir(UPLOAD_PATH . $subdir, 0755, true);
    }
    
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return UPLOAD_URL . $subdir . $filename;
    }
    return '';
}

function deleteImage($path) {
    if ($path && file_exists(__DIR__ . '/../' . $path)) {
        unlink(__DIR__ . '/../' . $path);
    }
}

function sanitize($str) {
    return htmlspecialchars(strip_tags(trim($str)), ENT_QUOTES, 'UTF-8');
}

function flash($msg, $type = 'success') {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}
