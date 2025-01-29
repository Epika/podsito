<?php
require_once 'check-auth.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = "../uploads/documents/";

// Create directory if not exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['pdf'])) {
            throw new Exception('No se recibió ningún archivo');
        }

        $file = $_FILES['pdf'];
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode([
                'success' => true,
                'message' => 'PDF subido correctamente',
                'path' => '/uploads/documents/' . $fileName
            ]);
        } else {
            throw new Exception('Error al mover el archivo: ' . error_get_last()['message']);
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}
?>