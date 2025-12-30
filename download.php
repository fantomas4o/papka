<?php
$uploadDir = 'uploads/';

if (isset($_GET['file'])) {
    $filename = basename($_GET['file']);
    $filePath = $uploadDir . $filename;
    
    if (file_exists($filePath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo 'Файлът не е намерен';
    }
} else {
    http_response_code(400);
    echo 'Невалидна заявка';
}
?>
