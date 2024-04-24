<?php
// Configuración de conexión a la base de datos
require_once "conexion.php";

// Obtener el nombre del documento desde la URL
$nombre_documento = $_GET['nombre'];

// Consulta SQL para obtener el documento desde la base de datos
$sql = "SELECT contenido, tipoArchivo FROM documentos WHERE nombre = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nombre_documento);
$stmt->execute();
$stmt->bind_result($documento, $tipoArchivo);
$stmt->fetch();

// Establecer las cabeceras adecuadas para el tipo de archivo
switch ($tipoArchivo) {
    case 'application/pdf':
        header('Content-type: application/pdf');
        break;
    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
        header('Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        break;
    default:
        // Tipo de archivo no compatible
        die("Tipo de archivo no compatible");
}

header('Content-Disposition: inline; filename="' . $nombre_documento . '"');
header('Content-Transfer-Encoding: binary');
header('Accept-Ranges: bytes');
echo $documento;

// Cerrar la conexión y liberar los recursos
$stmt->close();
$conn->close();
?>
