<?php
require_once 'check-auth.php';
header('Content-Type: application/json');

try {
    $category = $_POST['category'];
    $descripcion = $_POST['descripcion'] ?? ''; // Capturar descripción
    $audioDir = "../uploads/audio/$category/";
    $imageDir = "../uploads/images/$category/";
    $metadataDir = "../uploads/metadata/$category/";

    // Crear directorios si no existen
    if (!file_exists($audioDir)) mkdir($audioDir, 0755, true);
    if (!file_exists($imageDir)) mkdir($imageDir, 0755, true);
    if (!file_exists($metadataDir)) mkdir($metadataDir, 0755, true);

    // Procesar audio
    $audio = $_FILES['audio'];
    $audioName = time() . '_' . basename($audio['name']);
    $audioPath = $audioDir . $audioName;

    // Procesar imagen
    $image = $_FILES['image'];
    $imageName = pathinfo($audioName, PATHINFO_FILENAME) . '.jpg';
    $imagePath = $imageDir . $imageName;

    if (move_uploaded_file($audio['tmp_name'], $audioPath) &&
        move_uploaded_file($image['tmp_name'], $imagePath)) {
        
        // Guardar metadata (incluye descripción)
        $metadata = [
            'audio' => $audioName,
            'image' => $imageName,
            'descripcion' => $descripcion,
            'fecha' => date('Y-m-d H:i:s')
        ];
        
        file_put_contents(
            $metadataDir . pathinfo($audioName, PATHINFO_FILENAME) . '.json',
            json_encode($metadata)
        );

        echo json_encode([
            'success' => true,
            'audio' => $audioName,
            'image' => $imageName,
            'descripcion' => $descripcion,
            'html' => '
                <h6 class="audio-title">' . basename($audioName) . '</h6>
                <div class="descripcion-audio">' . htmlspecialchars($descripcion) . '</div>
                <div class="card horizontal">
                    <div class="card-image">
                        <img src="../uploads/images/'.$category.'/'.$imageName.'" 
                             alt="Imagen ilustrativa" 
                             style="width: 180px; height: 180px; object-fit: cover;">
                    </div>
                    <div class="card-stacked">
                        <div class="card-content">
                            <audio controls class="no-timeline">
                                <source src="../uploads/audio/'.$category.'/'.$audioName.'" type="audio/mpeg">
                            </audio>
                        </div>
                    </div>
                </div>'
        ]);
    } else {
        throw new Exception('Error al mover los archivos');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>