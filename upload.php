<?php
// Verificar si se ha enviado el formulario y si el campo de correo no está vacío
if (isset($_POST['submit']) && !empty($_POST['correo'])) {
    // Configuración de conexión a la base de datos MySQL
    require_once "conexion.php";

    // Ajustar la configuración de PHP para permitir la subida de archivos grandes
    ini_set('upload_max_filesize', '9000M'); // Tamaño máximo de archivo individual
    ini_set('post_max_size', '9000M'); // Tamaño máximo total de la solicitud POST
    ini_set('max_execution_time', 600); // Tiempo máximo de ejecución del script (en segundos)
    ini_set('memory_limit', '900M'); // Tamaño máximo de memoria permitido para el script

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $descripcion = $_POST['descripcion'];

    // Iterar sobre los archivos seleccionados
    foreach ($_FILES['archivo']['tmp_name'] as $key => $tmp_name) {
        $archivo_nombre = $_FILES['archivo']['name'][$key];
        $archivo_tipo = $_FILES['archivo']['type'][$key];
        $archivo_tamano = $_FILES['archivo']['size'][$key];
        $archivo_contenido = file_get_contents($_FILES['archivo']['tmp_name'][$key]);

        // Insertar el archivo en la base de datos
        $sql = "INSERT INTO videos (nombre, descripcion, archivos, correo) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nombre, $descripcion, $archivo_contenido, $correo);

        if ($stmt->execute()) {
            echo '<script>alert("Archivo subido correctamente: ' . $archivo_nombre . '"); window.location.href = "verVideos.php";</script>';
        } else {
            echo '<script>alert("Error al subir el archivo: ' . $archivo_nombre . '");</script>';
        }
    }

    $conn->close();
} elseif (isset($_POST['submit'])) {
    // Si el campo de correo está vacío, mostrar un mensaje de error
    echo '<script>alert("El campo de correo está vacío. Por favor, ingrese un correo válido."); window.location.href = "formularioVideos.php";</script>';
}
?>
