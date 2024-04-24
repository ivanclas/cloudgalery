<?php
session_start();

// Verificar si se ha enviado el formulario
if (isset($_POST['submit'])) {
    // Verificar si se han seleccionado archivos
    if (!empty($_FILES['documento']['name'][0])) {
        // Configuración de conexión a la base de datos MySQL
        require_once "conexion.php";

        // Obtener los datos del formulario
        $nombre_documento = $_POST['nombre'];
        $correo_usuario = $_SESSION['correo_usuario']; // Obtener el correo del usuario de la sesión

        // Preparar la consulta SQL
        $sql = "INSERT INTO documentos (nombre, correo, nombreArchivo, contenido, tipoArchivo) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Iterar sobre cada archivo seleccionado
        for ($i = 0; $i < count($_FILES['documento']['name']); $i++) {
            // Obtener información del archivo
            $nombre_archivo = $_FILES['documento']['name'][$i];
            $archivo_temporal = $_FILES['documento']['tmp_name'][$i];
            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);

            // Verificar si el archivo es PDF o Word
            if ($extension === 'pdf' || $extension === 'docx') {
                // Si es un archivo de Word, lo convertimos a PDF
                if ($extension === 'docx') {
                    // Nombre del archivo temporal para el archivo de Word
                    $nombre_temporal_docx = 'temp/' . uniqid() . '.docx';

                    // Mover el archivo de Word a la carpeta temporal
                    move_uploaded_file($archivo_temporal, $nombre_temporal_docx);

                    // Nombre del archivo temporal para el PDF
                    $nombre_temporal_pdf = 'temp/' . uniqid() . '.pdf';

                    // Comando para convertir el archivo de Word a PDF utilizando LibreOffice
                    $comando = "libreoffice --headless --convert-to pdf $nombre_temporal_docx --outdir temp";
                    shell_exec($comando);

                    // Actualizar el archivo temporal al PDF generado
                    $archivo_temporal = $nombre_temporal_pdf;

                    // Actualizar la extensión a PDF
                    $extension = 'pdf';
                }

                // Leer el contenido del archivo
                $contenido_documento = file_get_contents($archivo_temporal);

                // No modificamos el nombre del archivo final, mantenemos el nombre original
                $nombre_archivo_final = $nombre_archivo;

                // Obtener el tipo MIME del archivo
                $tipo_archivo = mime_content_type($archivo_temporal);

                // Insertar los datos en la base de datos
                $stmt->bind_param("ssbss", $nombre_documento, $correo_usuario, $nombre_archivo_final, $contenido_documento, $tipo_archivo);
                if ($stmt->execute()) {
                    echo "<script>alert('El documento \"$nombre_archivo\" se ha subido correctamente y se ha almacenado en la base de datos.'); window.location.href = 'formularioDocumento.php';</script>";
                } else {
                    echo "<script>alert('Error al almacenar el documento \"$nombre_archivo\" en la base de datos.'); window.location.href = 'formularioDocumento.php';</script>";
                }

                // Eliminar archivos temporales si se convirtió de Word a PDF
                if ($extension === 'docx') {
                    unlink($nombre_temporal_docx);
                    unlink($nombre_temporal_pdf);
                }
            } else {
                echo "<script>alert('El documento \"$nombre_archivo\" no es de un tipo válido (PDF o Word).'); window.location.href = 'formularioDocumento.php';</script>";
            }
        }

        // Cerrar la consulta preparada
        $stmt->close();
    } else {
        echo "<script>alert('No se ha seleccionado ningún archivo.'); window.location.href = 'formularioDocumento.php';</script>";
    }
} else {
    // Redirigir si se intenta acceder directamente a este archivo sin enviar el formulario
    header("Location: formularioDocumento.php");
    exit;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
