<?php
header('Content-Type: application/json');
header('Content-Type: application/json');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$uploadDir = "uploads/documents/";
$files = [];

try {
    if (is_dir($uploadDir)) {
        $pdfFiles = glob($uploadDir . "*.pdf");
        
        foreach ($pdfFiles as $file) {
            $files[] = [
                'nombre' => basename($file),
                'ruta' => '/' . $file
            ];
        }
    }
    
    echo json_encode(['success' => true, 'files' => array_reverse($files)]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>