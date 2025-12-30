<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    
    return rmdir($dir);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folder'])) {
    $folderName = $_POST['folder'];
    
    // Валидация
    if (empty($folderName) || strpos($folderName, '..') !== false) {
        echo json_encode(['success' => false, 'message' => 'Невалидно име на папка']);
        exit;
    }
    
    $folderPath = $uploadDir . $folderName;
    
    if (deleteDirectory($folderPath)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Грешка при изтриване']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Невалидна заявка']);
}
?>
