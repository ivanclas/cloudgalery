<?php
// Configuración de conexión a la base de datos MySQL
require_once "menu.php";
require_once "conexion.php";

// Verificar si el correo del usuario está almacenado en la sesión
if (isset($_SESSION['correo_usuario'])) {
    $correoUsuario = $_SESSION['correo_usuario'];

    // Consulta para obtener los videos asociados con el correo del usuario
    $sql = "SELECT * FROM videos WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correoUsuario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Inicializar la variable para almacenar la galería de medios
    $mediaGallery = '';

    // Mostrar los archivos en forma de galería
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $nombre = $row["nombre"];
            $descripcion = $row["descripcion"];
            $archivo_blob = $row["archivos"];
            $tipo = finfo_buffer(finfo_open(), $archivo_blob, FILEINFO_MIME_TYPE);

            // Construir el HTML para cada elemento de la galería
            $mediaGallery .= '<div class="media-item">';
            $mediaGallery .= "<h3>$nombre</h3>";
            $mediaGallery .= "<p>$descripcion</p>";

            // Mostrar el archivo según su tipo
            if (strpos($tipo, "image") !== false) {
                $mediaGallery .= "<img src='data:$tipo;base64," . base64_encode($archivo_blob) . "' alt='Imagen'>";
            } elseif (strpos($tipo, "video") !== false) {
                $mediaGallery .= "<video controls>";
                $mediaGallery .= "<source src='data:$tipo;base64," . base64_encode($archivo_blob) . "' type='$tipo'>";
                $mediaGallery .= "Tu navegador no soporta la reproducción de video.";
                $mediaGallery .= "</video>";
            } else {
                $mediaGallery .= "<p>Archivo no soportado: $tipo</p>";
            }

            // Enlace de eliminación
            $mediaGallery .= "<a class='delete-link' href='delete.php?id=" . $row['id'] . "'>Eliminar</a>";

            $mediaGallery .= "</div>";
        }
    } else {
        $mediaGallery = "No se encontraron archivos asociados con este usuario.";
    }

    $stmt->close();
} else {
    $mediaGallery = "Error: El correo del usuario no está almacenado en la sesión.";
}

$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
    <title>Ver Collages</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
           
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .media-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            grid-gap: 20px;
            margin-top: 20px;
        }
        .media-item {
            background-color: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .media-item:hover {
            transform: translateY(-5px);
        }
        .media-item img,
        .media-item video {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 10px 10px 0 0;
        }
        .media-item .content {
            padding: 20px;
        }
        .media-item h3 {
            margin-top: 0;
            margin-bottom: 10px;
            color: #333;
            font-size: 1.2rem;
        }
        .media-item p {
            margin: 0;
            color: #666;
            font-size: 1rem;
        }
        .media-item a.delete-link {
            display: block;
            margin-top: 10px;
            text-align: center;
            color: #e74c3c;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .media-item a.delete-link:hover {
            color: #c0392b;
        }
        .floating-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            line-height: 50px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
            z-index: 999;
            transition: background-color 0.3s ease;
        }
        .floating-button:hover {
            background-color: #2980b9;
        }
        .share-button {
            position: fixed;
            bottom: 20px;
            left: 20px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 24px;
            line-height: 50px;
            text-align: center;
            cursor: pointer;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.3);
            z-index: 999;
            transition: background-color 0.3s ease;
        }
        .share-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<h2>Galería Multimedia</h2>

<!-- Contenedor de medios -->
<div class="media-container">
    <!-- Galería de medios PHP generada -->
    <?php echo $mediaGallery; ?>
</div>

<!-- Botón flotante para agregar más videos -->
<button class="floating-button" onclick="location.href='formularioVideos.php';">+</button>

<!-- Librería de iconos -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-9LFHY5vPh5RFRzEdgNBt7CrjZ2iS7OZaNvFTdWiTGK1ffzS8kmXtzr8z5K7/P8i3yKDe7cNBljEsJW0bKDz0tA==" crossorigin="anonymous" referrerpolicy="no">

<!-- Botón flotante para compartir -->
<button class="share-button" onclick="compartir()"><i class="fas fa-share"></i></button>

<!-- Script JavaScript -->
<script>
    // Función para compartir
    function compartir() {
        if (navigator.share) {
            navigator.share({
                title: 'Galería Multimedia',
                text: '¡Echa un vistazo a esta increíble galería multimedia!',
                url: window.location.href
            }).then(() => {
                console.log('Enlace compartido exitosamente.');
            }).catch((error) => {
                console.error('Error al compartir enlace:', error);
            });
        } else {
            alert('La función de compartir no está soportada en tu navegador.');
        }
    }

    // Script para activar la pantalla completa al hacer clic en una imagen o video
    document.querySelectorAll('.media-item img, .media-item video').forEach(function(element) {
        element.addEventListener('click', function(e) {
            var rect = element.getBoundingClientRect();
            var offsetX = e.clientX - rect.left;
            var offsetY = e.clientY - rect.top;
            if (element.requestFullscreen) {
                if (offsetX < element.width && offsetY < element.height) {
                    element.requestFullscreen();
                }
            } else if (element.webkitRequestFullscreen) { /* Safari */
                if (offsetX < element.width && offsetY < element.height) {
                    element.webkitRequestFullscreen();
                }
            } else if (element.msRequestFullscreen) { /* IE11 */
                if (offsetX < element.width && offsetY < element.height) {
                    element.msRequestFullscreen();
                }
            }
        });
    });
</script>

</body>
</html>
