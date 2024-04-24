<?php
// Verificar si se proporciona un ID válido en la solicitud
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Configuración de conexión a la base de datos MySQL
    require_once "conexion.php";
    // Obtener el ID del archivo a descargar
    $id = $_GET['id'];

    // Consultar la base de datos para obtener el archivo correspondiente al ID
    $sql = "SELECT * FROM videos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar si se encontró un archivo con el ID proporcionado
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $nombre_archivo = $row['nombre'];
        $archivo_blob = $row['archivos'];

        // Determinar el tipo de archivo basado en la extensión del nombre de archivo
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        $tipo_mime = '';

        // Asignar el tipo MIME correspondiente
        switch ($extension) {
            case 'mp4':
                $tipo_mime = 'video/mp4';
                break;
            case 'jpg':
                $tipo_mime = 'image/jpeg';
                break;
            default:
                $tipo_mime = 'application/octet-stream';
        }

        // Configurar las cabeceras para la descarga del archivo
        header("Content-Type: $tipo_mime");
        header("Content-Disposition: attachment; filename=\"$nombre_archivo\"");

        // Imprimir el contenido del archivo
        echo $archivo_blob;
        exit; // Importante para detener la ejecución del script después de la descarga del archivo
    } else {
        echo "Archivo no encontrado.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "ID de archivo no válido.";
}
?>
