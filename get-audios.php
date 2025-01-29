<?php
header('Content-Type: application/json');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

$categoria = basename($_SERVER['HTTP_REFERER'], '.html');
$uploadDir = "uploads/audio/{$categoria}/";
$metadataDir = "uploads/metadata/{$categoria}/";
$adsDir = "uploads/ads/{$categoria}/";


try {
    $audioFiles = [];
    $adFiles = [];
    $combinedFiles = [];
    
    // Obtener audios
    if (is_dir($uploadDir)) {
        $audioFiles = glob($uploadDir . "*.{mp3,wav}", GLOB_BRACE);
    }
    
    // Obtener anuncios
    if (is_dir($adsDir)) {
        $adFiles = glob($adsDir . "*_desktop.{jpg,png}", GLOB_BRACE);
    }

    $combinedFiles = [];
    $adIndex = 0;
    
     $audioFiles = array_reverse($audioFiles);
    $adFiles = array_reverse($adFiles);

    $combinedFiles = [];
    $adIndex = 0;
    
    foreach ($audioFiles as $index => $file) {
        // Agregar audio
        $metadataFile = $metadataDir . pathinfo(basename($file), PATHINFO_FILENAME) . ".json";
        $descripcion = "";
        
        if (file_exists($metadataFile)) {
            $metadata = json_decode(file_get_contents($metadataFile), true);
            $descripcion = $metadata['descripcion'] ?? '';
        }
        
        $combinedFiles[] = [
            'tipo' => 'audio',
            'nombre' => basename($file),
            'ruta' => '/' . $file,
            'tipo_archivo' => mime_content_type($file),
            'descripcion' => $descripcion
        ];
        
        // Insertar anuncio después de cada audio
          // Insertar anuncio después de cada audio (usando solo el base name)
        if (isset($adFiles[$adIndex])) {
            $baseName = str_replace('_desktop.jpg', '', basename($adFiles[$adIndex]));
            $adMetadata = json_decode(file_get_contents(
                "uploads/metadata/ads/{$categoria}/{$baseName}.json"
            ), true);
            
             $combinedFiles[] = [
                'tipo' => 'anuncio',
                'imagen' => '/uploads/ads/'.$categoria.'/'.basename($adFiles[$adIndex]),
                'url' => $adMetadata['url'] ?? '#'

            ];
            $adIndex++;
        }
    }
    
    echo json_encode([
    'success' => true, 
    'files' => $combinedFiles  // Removed array_reverse
]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false, 
        'error' => $e->getMessage()
    ]);
}
?>