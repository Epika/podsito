<?php
require_once '../config/database.php';
require_once 'check-auth.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="metricas_' . date('Y-m-d') . '.csv"');

// Crear archivo CSV
$output = fopen('php://output', 'w');

// Encabezados CSV
fputcsv($output, ['Tipo', 'Nombre', 'Cantidad', 'Fecha']);

// Obtener datos de visitas
$visitsQuery = $conn->query("SELECT page, COUNT(*) as total, DATE(timestamp) as fecha 
                            FROM visits 
                            GROUP BY page, DATE(timestamp)");

while ($row = $visitsQuery->fetch_assoc()) {
    fputcsv($output, ['Visita', $row['page'], $row['total'], $row['fecha']]);
}

// Obtener datos de reproducciones
$playsQuery = $conn->query("SELECT audio_name, COUNT(*) as total, DATE(timestamp) as fecha 
                           FROM audio_plays 
                           GROUP BY audio_name, DATE(timestamp)");

while ($row = $playsQuery->fetch_assoc()) {
    fputcsv($output, ['Reproducción', $row['audio_name'], $row['total'], $row['fecha']]);
}

// Obtener datos de clicks en anuncios
$clicksQuery = $conn->query("SELECT ad_name, COUNT(*) as total, DATE(timestamp) as fecha 
                            FROM ad_clicks 
                            GROUP BY ad_name, DATE(timestamp)");

while ($row = $clicksQuery->fetch_assoc()) {
    fputcsv($output, ['Click Anuncio', $row['ad_name'], $row['total'], $row['fecha']]);
}

fclose($output);
exit;
?>