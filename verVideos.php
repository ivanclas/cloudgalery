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
    <title>Galería Multimedia</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Estilos CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #3498db, #8e44ad); /* Fondo degradado */
            color: #fff; /* Texto en color blanco */
            margin: 0;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
        }
        .media-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .media-item {
            margin: 10px;
            text-align: center;
            width: calc(50% - 20px); /* Dos elementos por fila */
            max-width: 300px;
            border: 2px solid #fff; /* Borde blanco */
            border-radius: 10px; /* Esquinas redondeadas */
            overflow: hidden; /* Evitar que los elementos sobresalgan */
            cursor: pointer; /* Cambiar el cursor al puntero al pasar sobre los elementos */
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.5); /* Sombra */
        }
        .media-item img,
        .media-item video {
            max-width: 100%; /* Ajustar el ancho máximo de las imágenes y videos al contenedor */
            height: auto; /* Altura automática */
            object-fit: cover; /* Ajustar la imagen o video al contenedor */
            border-bottom: 2px solid #fff; /* Borde inferior blanco */
            transition: transform 0.3s ease; /* Transición suave para el zoom */
        }
        .media-item:hover img,
        .media-item:hover video {
            transform: scale(1.1); /* Aumentar el tamaño al pasar el ratón */
        }
        .delete-link {
            display: block;
            margin-top: 10px;
            text-align: center;
            text-decoration: none;
            color: #fff; /* Enlaces en color blanco */
            padding: 5px 0;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }
        .delete-link:hover {
            background-color: rgba(255, 255, 255, 0.2); /* Fondo semi-transparente al pasar el ratón */
        }
        @media (max-width: 768px) {
            .media-item {
                width: calc(100% - 20px); /* Un elemento por fila en pantallas pequeñas */
            }
        }
        /* Estilos para el botón flotante */
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
        }
        /* Estilos para el botón de compartir */
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

