<?php
require_once '../config/database.php';

// Obtener IP del visitante
$ip = $_SERVER['REMOTE_ADDR'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    switch($data['type']) {
        case 'visit':
            $stmt = $conn->prepare("INSERT INTO visits (page, ip_address) VALUES (?, ?)");
            $stmt->bind_param("ss", $data['page'], $ip);
            break;
            
        case 'play':
            $stmt = $conn->prepare("INSERT INTO audio_plays (audio_name, category, ip_address) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $data['audio'], $data['category'], $ip);
            break;
            
        case 'ad_click':
            $stmt = $conn->prepare("INSERT INTO ad_clicks (ad_name, category, ip_address) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $data['ad'], $data['category'], $ip);
            break;
    }
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
}
?>