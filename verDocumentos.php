<?php require_once "menu.php"; ?>
<?php
// Asegurarse de que session_start() se llame solo una vez
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Configuración de conexión a la base de datos
require_once "conexion.php";

// Verificar la sesión del usuario
if (!isset($_SESSION['correo_usuario'])) {
    // Redirigir si el usuario no está autenticado
    header("Location: index.php");
    exit;
}

// Obtener el correo del usuario de la sesión
$correo_usuario = $_SESSION['correo_usuario'];

// Consulta SQL para obtener los documentos del usuario actual
$sql = "SELECT idDocumento, nombre, tipoArchivo FROM documentos WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo_usuario);
$stmt->execute();
$result = $stmt->get_result();

// Crear un array para almacenar los documentos
$documents = [];

// Iterar sobre los resultados y almacenarlos en el array
while ($row = $result->fetch_assoc()) {
    // Obtener la extensión del archivo
    $extension = pathinfo($row['tipoArchivo'], PATHINFO_EXTENSION);

    // Agregar el documento al array
    $documents[] = [
        'idDocumento' => $row['idDocumento'],
        'nombre' => $row['nombre'],
        'extension' => $extension
    ];
}

// Cerrar la conexión y liberar los recursos
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galería de Documentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Agregamos Font Awesome para los iconos -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .document-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 20px;
        }

        .document-card {
            width: 200px;
            margin: 10px;
            padding: 10px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .document-card p {
            margin-top: 10px;
            text-align: center;
        }

        .delete-link {
            display: block;
            text-align: center;
            color: #ff0000;
            text-decoration: none;
            margin-top: 5px;
        }

        .delete-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <center> <h2>Galería de Documentos</h2> </center>
    <div class="document-container" id="document-container">
        <?php foreach ($documents as $document): ?>
            <div class="document-card">
                <a href="#" onclick="openPDFViewer('visualizar_documento.php?nombre=<?php echo $document['nombre']; ?>')">
                    <i class="far fa-file-pdf" style="font-size: 64px;"></i>
                </a>
                <p><?php echo $document['nombre']; ?></p>
                <a href="eliminar_documento.php?idDocumento=<?php echo $document['idDocumento']; ?>" class="delete-link" onclick="return confirm('¿Estás seguro de que quieres eliminar este documento?')">Eliminar</a>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        function openPDFViewer(pdfUrl) {
            // Configurar el tamaño de la ventana emergente
            var width = 800;
            var height = 600;

            // Calcular la posición centrada de la ventana emergente
            var left = (screen.width - width) / 2;
            var top = (screen.height - height) / 2;

            // Abrir la ventana emergente
            window.open(pdfUrl, '_blank', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, width=' + width + ', height=' + height + ', top=' + top + ', left=' + left);
        }
    </script>
</body>
</html>
