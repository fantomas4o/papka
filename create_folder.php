<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['folder'])) {
    $folderName = $_POST['folder'];
    
    // Валидация на името
    if (empty($folderName) || strpos($folderName, '..') !== false) {
        echo json_encode(['success' => false, 'message' => 'Невалидно име на папка']);
        exit;
    }
    
    $folderPath = $uploadDir . $folderName;
    
    if (file_exists($folderPath)) {
        echo json_encode(['success' => false, 'message' => 'Папката вече съществува']);
        exit;
    }
    
    if (mkdir($folderPath, 0755, true)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Грешка при създаване на папка']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Невалидна заявка']);
}
?>
