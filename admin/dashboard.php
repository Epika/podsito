
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
</head>
<body>
    
    <?php
    // Activar debug
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    // Definir ruta base
    define('BASE_PATH', dirname(dirname(__FILE__)));
    
    require_once BASE_PATH . '/admin/check-auth.php';
    require_once BASE_PATH . '/config/database.php';
    
    
    // Determinar período seleccionado
$period = isset($_GET['period']) ? $_GET['period'] : 'all';
$whereClause = '';

if ($period === '30days') {
    $whereClause = "WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
}

try {
    // Verificar conexión
    if (!$conn) {
        throw new Exception("Error de conexión a base de datos");
    }

    // Obtener métricas con filtro de período
    $visits = $conn->query("SELECT COUNT(*) as total FROM visits $whereClause");
    $plays = $conn->query("SELECT audio_name, COUNT(*) as plays 
                          FROM audio_plays 
                          $whereClause 
                          GROUP BY audio_name");
    $clicks = $conn->query("SELECT ad_name, COUNT(*) as clicks 
                           FROM ad_clicks 
                           $whereClause 
                           GROUP BY ad_name");

    if (!$visits || !$plays || !$clicks) {
        throw new Exception("Error en consultas: " . $conn->error);
    }

} catch (Exception $e) {
    echo '<div class="container red-text">Error: ' . $e->getMessage() . '</div>';
}

    try {
        // Verificar conexión
        if (!$conn) {
            throw new Exception("Error de conexión a base de datos");
        }

        // Obtener métricas con manejo de errores
        $visits = $conn->query("SELECT COUNT(*) as total FROM visits");
        $plays = $conn->query("SELECT audio_name, COUNT(*) as plays FROM audio_plays GROUP BY audio_name");
        $clicks = $conn->query("SELECT ad_name, COUNT(*) as clicks FROM ad_clicks GROUP BY ad_name");

        if (!$visits || !$plays || !$clicks) {
            throw new Exception("Error en consultas: " . $conn->error);
        }

    } catch (Exception $e) {
        echo '<div class="container red-text">Error: ' . $e->getMessage() . '</div>';
    }
    ?>
    
    
    
    
    
   
           <div class="nav-wrapper black">
            <center>
            <a  class="brand-logo"><h5>PODSITO Panel Admin</h5></a>
            
            <ul id="nav-mobile" class="center-align">
                <li><a href="logout.php">Salir</a></li>
            </ul>
        </div>
    </nav>
    
    <div class="container">
        
            <div class="col s12">
                <h5>Gestión de Contenido (categorías)</h5>
                <div class="collection">
                    <a href="upload.php?cat=novedades" class="collection-item">Novedades</a>
                    <a href="upload.php?cat=electricidad" class="collection-item">Electricidad</a>
                    <a href="upload.php?cat=seguridad" class="collection-item">Seguridad</a>
                    <a href="upload.php?cat=productos" class="collection-item">Productos</a>
                    <a href="pdf-upload.php" class="collection-item">Gestionar PDFs</a>
                </div>
            </div>
        
    </div>
    <div class="container">
    <h5>Gestión de Anuncios</h5>
    <form id="adForm" enctype="multipart/form-data">
        <div class="input-field">
            <select name="category" required>
                <option value="novedades">Novedades</option>
                <option value="electricidad">Electricidad</option>
                <option value="seguridad">Seguridad</option>
                <option value="productos">Productos</option>
            </select>
            <label>Categoría</label>
        </div>
        
        <!-- Imagen Desktop -->
        <div class="file-field input-field">
            <div class="btn blue">
                <span>Imagen Desktop (728x90px)</span>
                <input type="file" name="adImageDesktop" accept="image/*" required>
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
            </div>
        </div>

        <!-- Imagen Mobile -->
        <div class="file-field input-field">
            <div class="btn blue">
                <span>Imagen Mobile (320x100px)</span>
                <input type="file" name="adImageMobile" accept="image/*" required>
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text">
            </div>
        </div>

        <div class="input-field">
            <input type="url" name="adUrl" required>
            <label>URL del anuncio</label>
        </div>
        <button class="btn waves-effect waves-light" type="submit">
            Subir Anuncio
        </button>
    </form>
    <div><br><br></div>
</div>

<div class="container">
            <div class="col s12">
                <h5>Métricas de la App</h5>
                
               
        <div class="card-panel">
             <a>seleccionar período de visualización</a>
            <div class="input-field">
                <select id="periodSelect" onchange="changePeriod(this.value)">
                    <option value="all" <?php echo $period === 'all' ? 'selected' : ''; ?>>Todo el tiempo</option>
                    <option value="30days" <?php echo $period === '30days' ? 'selected' : ''; ?>>Últimos 30 días</option>
                </select>
               
               
                                    
            </div>

                <div class="card-panel">
                    <h5>Estadísticas Generales</h5>
                    <table class="striped">
                        <tbody>
                            <tr>
                                <td><strong>Visitas Totales:</strong></td>
                                <td><?php echo $visits->fetch_assoc()['total']; ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h5>Reproducciones por Audio</h5>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Audio</th>
                                <th>Reproducciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $plays->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['audio_name']; ?></td>
                                <td><?php echo $row['plays']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <h5>Clicks por Anuncio</h5>
                    <table class="striped">
                        <thead>
                            <tr>
                                <th>Anuncio</th>
                                <th>Clicks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $clicks->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $row['ad_name']; ?></td>
                                <td><?php echo $row['clicks']; ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <div class="center-align" style="margin-top: 20px;">
                        <a href="metrics.php?download=1&period=<?php echo $period; ?>" class="btn waves-effect waves-light">
    Descargar Reporte CSV
</a>
                    </div>
                </div>
            </div>
        </div>
        <div> <br></div>
    </div>





<script>
document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);

    document.getElementById('adForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);

        try {
            const response = await fetch('handle-ads.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            if (result.success) {
                M.toast({html: 'Anuncio subido correctamente', classes: 'green'});
                e.target.reset();
            } else {
                throw new Error(result.error);
            }
        } catch (error) {
            M.toast({html: 'Error al subir anuncio', classes: 'red'});
            console.error(error);
        }
    });
});
</script>


<script>
function changePeriod(period) {
    window.location.href = 'dashboard.php?period=' + period;
}

document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('select');
    M.FormSelect.init(elems);
});
</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>