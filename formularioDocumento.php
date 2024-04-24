<?php require_once "menu.php"; ?>

<?php
// Verificar si se ha enviado un archivo
if(isset($_FILES['documento'])){
    $nombre_archivo = $_FILES['documento']['name'];
    $tipo_archivo = $_FILES['documento']['type'];
    $archivo_temporal = $_FILES['documento']['tmp_name'];

    // Verificar que sea un archivo de Word o PDF
    if($tipo_archivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" || $tipo_archivo == "application/pdf") {
        // Si es un archivo de Word, convertirlo a PDF
        if($tipo_archivo == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
            // Guardar el archivo temporalmente en el servidor
            $destino_docx = 'archivos_temporales/' . $nombre_archivo;
            move_uploaded_file($archivo_temporal, $destino_docx);

            // Convertir el archivo de Word a PDF utilizando LibreOffice
            $pdf_destino = 'archivos_temporales/' . pathinfo($nombre_archivo, PATHINFO_FILENAME) . '.pdf';
            $comando = "libreoffice --headless --convert-to pdf $destino_docx --outdir archivos_temporales";
            $output = shell_exec($comando);

            // Verificar si la conversión fue exitosa
            if (file_exists($pdf_destino)) {
                // Establecer el destino como el PDF convertido
                $nombre_archivo = pathinfo($nombre_archivo, PATHINFO_FILENAME) . '.pdf';
                $archivo_temporal = $pdf_destino;
            } else {
                // Mostrar un mensaje de error si la conversión falló
                echo "Error: No se pudo convertir el archivo a PDF. Asegúrate de que LibreOffice esté instalado y configurado correctamente.";
                exit;
            }
        }

        // Devolver el archivo al usuario o hacer algo con él
        header('Content-Type: ' . $tipo_archivo);
        header('Content-Disposition: attachment; filename="' . basename($nombre_archivo) . '"');
        readfile($archivo_temporal);

        // Eliminar los archivos temporales después de su uso
        unlink($archivo_temporal);
    } else {
        // Si no es un archivo de Word o PDF, manejar la situación según necesites
        echo "El archivo debe ser un documento de Word (.docx) o un archivo PDF (.pdf).";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Documentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Agregamos Font Awesome para los iconos -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
          
        }

        form {
            max-width: 400px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold; /* Agregamos negrita para las etiquetas */
        }

        input[type="text"],
        input[type="file"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="file"] {
            cursor: pointer;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        /* Estilos adicionales para el icono */
        .file-input-icon {
            display: inline-block;
            background-color: #007bff;
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        /* Estilos para el visor de PDF */
        .pdf-viewer {
            position: relative;
            width: 50%;
            height: 600px; /* Altura reducida del visor de PDF */
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: auto;
        }

        /* Estilos para el botón flotante */
        .floating-button {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <h2>Subir Documentos</h2>
    <form action="procesarSubida.php" method="post" enctype="multipart/form-data">
        <label for="nombre">Nombre del documento:</label>
        <input type="text" id="nombre" name="nombre" required><br>

        <!-- Agregamos un icono para el campo de selección de archivos -->
        <label for="documento" class="file-input-icon" id="documento-label">
            <i class="fas fa-file-upload"></i> Seleccionar documento(s) PDF
        </label>
        <input type="file" id="documento" name="documento[]" style="display: none;" multiple required accept=".pdf">

        <input type="submit" value="Subir Documento(s)" name="submit">
    </form>

    <!-- Visor de PDF -->
    <div class="pdf-viewer" id="pdf-viewer"></div>

    <!-- Botón flotante de visualización -->
    <button class="floating-button" onclick="redirectToVerDocumentos()"><i class="fas fa-eye"></i></button>

    <script>
        function redirectToVerDocumentos() {
            window.location.href = "verDocumentos.php"; // Redirige al usuario a verDocumentos.php
        }
  
        
        // Función para mostrar el visor de PDF después de seleccionar el archivo
        document.getElementById('documento').addEventListener('change', function(event) {
            var files = event.target.files;
            var pdfViewer = document.getElementById('pdf-viewer');
            pdfViewer.innerHTML = ''; // Limpiar el contenido del visor antes de agregar nuevos documentos

            for (var i = 0; i < files.length; i++) {
                var fileURL = URL.createObjectURL(files[i]);
                pdfViewer.innerHTML += '<embed src="' + fileURL + '" type="application/pdf" width="100%" height="100%" />';
            }

            // Actualizar el texto del label para mostrar el nombre del archivo seleccionado
            var labelText = "";
            for (var i = 0; i < files.length; i++) {
                labelText += files[i].name; // Agregar el nombre del archivo
                if (i !== files.length - 1) {
                    labelText += ", "; // Agregar coma y espacio si no es el último archivo
                }
            }
            document.getElementById('documento-label').innerText = labelText;
        });
    </script>
</body>
</html>
