<?php
require_once 'check-auth.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

$uploadDir = "../uploads/documents/";

if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

// Handle POST requests for file upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['pdf'])) {
            throw new Exception('No se recibió ningún archivo');
        }

        $file = $_FILES['pdf'];
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode([
                'success' => true,
                'message' => 'PDF subido correctamente',
                'path' => '/uploads/documents/' . $fileName
            ]);
        } else {
            throw new Exception('Error al mover el archivo');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir PDFs</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    <nav>
        <div class="nav-wrapper blue">
            <a href="dashboard.php" class="brand-logo left">&larr; Volver</a>
            <span class="brand-logo center">Subir PDFs</span>
        </div>
    </nav>

    <div class="container">
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="file-field input-field">
                <div class="btn">
                    <span>PDF</span>
                    <input type="file" name="pdf" accept=".pdf">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Seleccionar archivo PDF">
                </div>
            </div>
            <button class="btn waves-effect waves-light" type="submit">
                Subir PDF
            </button>
        </form>

        <div id="pdfList" class="section"></div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const formData = new FormData(e.target);
            const fileInput = e.target.querySelector('input[type="file"]');
            
            if (fileInput.files.length === 0) {
                M.toast({html: 'Seleccione un archivo PDF', classes: 'red'});
                return;
            }

            try {
                const response = await fetch('pdf-upload.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    M.toast({html: 'PDF subido correctamente', classes: 'green'});
                    e.target.reset();
                    location.reload();
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                M.toast({html: 'Error al subir PDF', classes: 'red'});
                console.error(error);
            }
        });
    </script>
</body>
</html>