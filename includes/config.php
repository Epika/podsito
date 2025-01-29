<?php
define('UPLOAD_DIR', 'uploads/audio/');
define('ALLOWED_TYPES', ['audio/mp3', 'audio/wav']);
define('MAX_FILE_SIZE', 30 * 1024 * 1024); // 30MB

// Crear directorios si no existen
$categories = ['novedades', 'electricidad', 'seguridad', 'productos', 'descargas'];
foreach ($categories as $category) {
    $path = UPLOAD_DIR . $category;
    if (!file_exists($path)) {
        mkdir($path, 0755, true);
    }
}
?>