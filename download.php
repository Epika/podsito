<?php
if (isset($_GET['file'])) {
    $file = $_GET['file'];
    $filepath = "uploads/documents/" . basename($file);
    
    if (file_exists($filepath)) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: no-cache');
        readfile($filepath);
        exit;
    }
}
?>