<?php
header('Content-Type: application/json');

// Obtener categoría de la URL
$categoria = basename($_SERVER['HTTP_REFERER'], '.html');
$uploadDir = "uploads/audio/{$categoria}/";

// Crear directorio si no existe
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

try {
    if (!isset($_FILES['audio'])) {
        throw new Exception('No se recibió el archivo');
    }

    $file = $_FILES['audio'];
    $fileName = time() . '_' . basename($file['name']);
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        echo json_encode([
            'success' => true,
            'message' => 'Archivo subido correctamente',
            'path' => '/' . $targetPath
        ]);
    } else {
        throw new Exception('Error al mover el archivo');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>