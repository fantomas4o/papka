<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file'])) {
    $filename = basename($_POST['file']);
    $filePath = $uploadDir . $filename;
    
    if (file_exists($filePath)) {
        if (unlink($filePath)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Грешка при изтриване']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Файлът не съществува']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Невалидна заявка']);
}
?>
