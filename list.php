<?php
header('Content-Type: application/json');

$uploadDir = 'uploads/';
$folder = isset($_GET['folder']) ? $_GET['folder'] : '';

// Валидация
if (strpos($folder, '..') !== false) {
    echo json_encode([]);
    exit;
}

$currentDir = $uploadDir . $folder;
$items = [];

if (is_dir($currentDir)) {
    $scanItems = scandir($currentDir);
    
    foreach ($scanItems as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $itemPath = $currentDir . $item;
        $relativePath = $folder . $item;
        
        if (is_dir($itemPath)) {
            // Преброяване на елементи в папката
            $count = count(array_diff(scandir($itemPath), ['.', '..']));
            
            $items[] = [
                'name' => $item,
                'isFolder' => true,
                'count' => $count,
                'modified' => filemtime($itemPath)
            ];
        } else {
            $items[] = [
                'name' => $item,
                'path' => $relativePath,
                'size' => filesize($itemPath),
                'type' => mime_content_type($itemPath),
                'isFolder' => false,
                'modified' => filemtime($itemPath)
            ];
        }
    }
}

// Сортиране: папки първи, после по дата
usort($items, function($a, $b) {
    if ($a['isFolder'] !== $b['isFolder']) {
        return $b['isFolder'] - $a['isFolder'];
    }
    return $b['modified'] - $a['modified'];
});

echo json_encode($items);
?>
