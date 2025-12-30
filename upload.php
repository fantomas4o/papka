<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $folder = isset($_POST['folder']) ? $_POST['folder'] : '';
    
    // Валидация на папката
    if (!empty($folder) && strpos($folder, '..') !== false) {
        echo json_encode(['success' => false, 'message' => 'Невалидна папка']);
        exit;
    }
    
    $targetDir = $uploadDir . $folder;
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }
    
    $filename = basename($file['name']);
    $targetPath = $targetDir . $filename;
    
    // Проверка за вече съществуващ файл
    if (file_exists($targetPath)) {
        $pathInfo = pathinfo($filename);
        $filename = $pathInfo['filename'] . '_' . time() . '.' . $pathInfo['extension'];
        $targetPath = $targetDir . $filename;
    }
    
    // Проверка за размер (макс 50MB)
    if ($file['size'] > 50 * 1024 * 1024) {
        echo json_encode(['success' => false, 'message' => 'Файлът е твърде голям (макс 50MB)']);
        exit;
    }
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode([
            'success' => true,
            'filename' => $filename
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Грешка при качване на файла'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Няма качен файл'
    ]);
}
?>
