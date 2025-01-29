<?php
require_once 'check-auth.php';
$category = isset($_GET['cat']) ? $_GET['cat'] : '';
$uploadDir = "../uploads/audio/{$category}/";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Subir Audio e Imagen</title>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    </head>
    <body>
        <nav>
            <div class="nav-wrapper blue">
                <a href="dashboard.php" class="brand-logo left">&larr; Volver</a>
            </div>
        </nav>

        <div class="container">
            <h4>Subir Audio - <?php echo ucfirst($category); ?></h4>
            
            <form id="uploadForm" enctype="multipart/form-data">
                <!-- Paso 1: Audio -->
                <div class="step-audio active">
                    <h5>Paso 1: Seleccionar Audio</h5>
                    <div class="file-field input-field">
                        <div class="btn blue">
                            <span>Audio</span>
                            <input type="file" name="audio" accept=".mp3,.wav" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                    <div class="input-field">
        <textarea name="descripcion" class="materialize-textarea" required></textarea>
        <label for="descripcion">Descripción del audio</label>
    </div>
    <button class="btn next-step">Siguiente</button>
</div>
                   

                <!-- Paso 2: Imagen -->
                <div class="step-image" style="display:none;">
                    <h5>Paso 2: Seleccionar Imagen</h5>
                    <div class="file-field input-field">
                        <div class="btn blue">
                            <span>Imagen</span>
                            <input type="file" name="image" accept="image/*" required>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>
                    <button class="btn" type="submit">Subir</button>
                </div>
            </form>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script>
            document.querySelector('.next-step').addEventListener('click', (e) => {
                e.preventDefault();
                document.querySelector('.step-audio').style.display = 'none';
                document.querySelector('.step-image').style.display = 'block';
            });

            document.getElementById('uploadForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                const formData = new FormData(e.target);
                formData.append('category', '<?php echo $category; ?>');

                try {
                    const response = await fetch('handle-upload.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();
                    if (result.success) {
                        M.toast({html: 'Archivos subidos correctamente', classes: 'green'});
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        throw new Error(result.error);
                    }
                } catch (error) {
                    M.toast({html: 'Error al subir archivos', classes: 'red'});
                    console.error(error);
                }
            });
        </script>
    </body>
    </html>
    <?php
    exit;
}

// Handle file upload POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!isset($_FILES['audio'])) {
            throw new Exception('No se recibió el archivo');
        }

        $file = $_FILES['audio'];
        $fileName = time() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            echo json_encode([
                'success' => true,
                'file' => $fileName,
                'html' => '<audio controls class="no-timeline"><source src="/uploads/audio/'.$category.'/'.$fileName.'" type="audio/mpeg"></audio>'
                ]);
        
                
        } else {
            throw new Exception('Error al mover el archivo');
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
    
    
}
?>