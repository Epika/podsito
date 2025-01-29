<?php
session_start();
header('Content-Type: application/json');

// Habilitar errores para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Credenciales
$admin_user = "admin";
$admin_pass = "podsito";

// Log input data
error_log("Received request: " . file_get_contents('php://input'));

$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'error' => 'Invalid JSON']);
    exit;
}

if (isset($data['username']) && isset($data['password']) && 
    $data['username'] === $admin_user && $data['password'] === $admin_pass) {
    $_SESSION['admin'] = true;
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false, 
        'error' => 'Invalid credentials',
        'debug' => [
            'username_match' => $data['username'] === $admin_user,
            'password_match' => $data['password'] === $admin_pass
        ]
    ]);
}
?>