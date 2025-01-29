<?php
header('Content-Type: application/json');

function countFiles($dir, $extensions) {
    $total = 0;
    if (is_dir($dir)) {
        $total += count(glob($dir . "*{" . implode(',', $extensions) . "}", GLOB_BRACE));
    }
    return $total;
}

// Definir categorías y extensiones
$categories = ['novedades', 'electricidad', 'seguridad', 'productos', 'descargas'];
$audioExt = ['.mp3', '.wav'];
$pdfExt = ['.pdf'];
$totalFiles = 0;

// Contar archivos de audio
foreach ($categories as $category) {
    $audioDir = "uploads/audio/" . $category . "/";
    $totalFiles += countFiles($audioDir, $audioExt);
}

// Contar archivos PDF
$pdfDir = "uploads/documents/";
$totalFiles += countFiles($pdfDir, $pdfExt);

echo json_encode(['total' => $totalFiles]);
?>