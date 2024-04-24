<?php
// Asegurarse de que session_start() se llame solo una vez
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verificar la sesión del usuario
if (!isset($_SESSION['correo_usuario'])) {
    // Redirigir si el usuario no está autenticado
    header("Location: index.php");
    exit;
}

// Verificar si se ha proporcionado el idDocumento a eliminar
if (isset($_GET['idDocumento'])) {
    // Obtener el idDocumento
    $idDocumento = $_GET['idDocumento'];

    // Configuración de conexión a la base de datos
    require_once "conexion.php";

    // Obtener el correo del usuario de la sesión
    $correo_usuario = $_SESSION['correo_usuario'];

    // Consulta SQL para eliminar el documento de la base de datos
    $sql = "DELETE FROM documentos WHERE idDocumento = ? AND correo = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    
    // Verificar si la preparación de la consulta fue exitosa
    if ($stmt) {
        // Vincular parámetros
        $stmt->bind_param("ss", $idDocumento, $correo_usuario);
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir de vuelta a la galería de documentos después de eliminar el documento
            header("Location: verDocumentos.php");
            exit;
        } else {
            // Manejar cualquier error que ocurra durante la eliminación del documento
            echo "Error al eliminar el documento.";
        }

        // Cerrar la consulta preparada
        $stmt->close();
    } else {
        // Manejar el caso de error en la preparación de la consulta
        echo "Error al preparar la consulta.";
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    // Redirigir si no se proporciona el idDocumento a eliminar
    header("Location: verDocumentos.php");
    exit;
}
?>
