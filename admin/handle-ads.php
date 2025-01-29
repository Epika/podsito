<?php
require_once 'check-auth.php';
header('Content-Type: application/json');

try {
    $category = $_POST['category'];
    $adsDir = "../uploads/ads/$category/";
    $metadataDir = "../uploads/metadata/ads/$category/";
    
    if (!file_exists($adsDir)) mkdir($adsDir, 0755, true);
    if (!file_exists($metadataDir)) mkdir($metadataDir, 0755, true);

    // Procesar imagen Desktop
    $adDesktop = $_FILES['adImageDesktop'];
    $baseFileName = time() . '_ad';
    $desktopName = $baseFileName . '_desktop.jpg';
    $desktopPath = $adsDir . $desktopName;

    // Procesar imagen Mobile
    $adMobile = $_FILES['adImageMobile'];
    $mobileName = $baseFileName . '_mobile.jpg';
    $mobilePath = $adsDir . $mobileName;

    // Limpiar URL
    $adUrl = filter_var($_POST['adUrl'], FILTER_SANITIZE_URL);
    
    if (move_uploaded_file($adDesktop['tmp_name'], $desktopPath) &&
        move_uploaded_file($adMobile['tmp_name'], $mobilePath)) {
        
        $metadata = [
            'desktop' => $desktopName,
            'mobile' => $mobileName,
            'url' => str_replace('\\', '', $adUrl),
            'fecha' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents(
            $metadataDir . $baseFileName . '.json',
            json_encode($metadata, JSON_UNESCAPED_SLASHES)
        );

        echo json_encode([
            'success' => true,
            'desktop' => $desktopName,
            'mobile' => $mobileName,
            'url' => $adUrl
        ], JSON_UNESCAPED_SLASHES);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>