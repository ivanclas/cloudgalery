<?php
// Verificar si se ha enviado el formulario y si el campo de correo no está vacío
if (isset($_POST['submit']) && !empty($_POST['correo'])) {
    // Configuración de conexión a la base de datos MySQL
    require_once "conexion.php";

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $descripcion = $_POST['descripcion'];

    // Inicializar un array para almacenar los datos de las imágenes
    $imagenes_data = [];

    // Iterar sobre los archivos seleccionados
    foreach ($_FILES['archivo']['tmp_name'] as $key => $tmp_name) {
        // Obtener información sobre el tipo de imagen
        $image_info = getimagesize($tmp_name);

        // Verificar si el archivo es una imagen y si es válido
        if ($image_info !== false && in_array($image_info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF])) {
            // Obtener el contenido del archivo
            $imagen_data = file_get_contents($tmp_name);

            // Agregar el contenido del archivo al array de imágenes
            $imagenes_data[] = $imagen_data;
        } else {
            // Si el archivo no es una imagen válida, mostrar un mensaje de error
            die('Error al cargar el archivo ' . $_FILES['archivo']['name'][$key] . ': No es una imagen válida.');
        }
    }

    // Serializar el array de imágenes para almacenarlo en la base de datos
    $imagenes_serializadas = serialize($imagenes_data);

    // Insertar la información del collage en la base de datos
    $sql = "INSERT INTO videos (nombre, descripcion, archivos, correo) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nombre, $descripcion, $imagenes_serializadas, $correo);

    // Ejecutar la consulta preparada
    if ($stmt->execute()) {
        echo '<script>alert("Collage guardado correctamente.");window.location.href = "verCollages.php";</script>';
    } else {
        echo '<script>alert("Error al guardar el collage."); window.location.href = "formularioCollages.php";</script>';
    }

    // Cerrar la conexión
    $conn->close();
} elseif (isset($_POST['submit'])) {
    // Si el campo de correo está vacío, mostrar un mensaje de error
    echo '<script>alert("El campo de correo está vacío. Por favor, ingrese un correo válido."); window.location.href = "formularioCollages.php";</script>';
}
?>
