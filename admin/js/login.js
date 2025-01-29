<?php
session_start();
header('Content-Type: application/json');

// Cambiar estas credenciales
$admin_user = "admin";
$admin_pass = "tu_contraseña_segura";

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

if (isset($data['username']) && isset($data['password']) && $data['username'] === $admin_user && $data['password'] === $admin_pass) {
    $_SESSION['admin'] = true;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>