<?php
// Verificar si se ha enviado el parámetro 'id' y si es un número válido
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    // Obtener el ID del archivo a eliminar
    $id = $_GET['id'];

    // Verificar si se ha confirmado la eliminación
    if(isset($_GET['confirmed']) && $_GET['confirmed'] === "true") {
        // Configurar la conexión a la base de datos
        require_once "conexion.php";

        // Consulta SQL para eliminar el archivo de la base de datos
        $sql = "DELETE FROM videos WHERE id = $id";

        if ($conn->query($sql) === TRUE) {
            // Redirigir de vuelta a la página de la galería después de eliminar el archivo
            header("Location: verVideos.php");
            exit();
        } else {
            echo "Error al eliminar el archivo: " . $conn->error;
        }

        // Cerrar la conexión
        $conn->close();
    } else {
        // Si la eliminación no ha sido confirmada, mostrar el cuadro de diálogo de confirmación
        echo "<script>";
        echo "if(confirm('¿Estás seguro de que deseas eliminar este archivo?')) {";
        echo "    window.location.href = 'delete.php?id=$id&confirmed=true';";
        echo "} else {";
        echo "    window.location.href = 'verVideos.php';";
        echo "}";
        echo "</script>";
    }
} else {
    // Si no se proporciona un ID válido, redirigir a la página de la galería
    header("Location: verVideos.php");
    exit();
}
?>
