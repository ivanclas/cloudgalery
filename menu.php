<?php
session_start();

// Verificar si el correo electrónico está presente en la URL y almacenarlo en la sesión si lo está
if (isset($_GET['correo'])) {
    $_SESSION['correo_usuario'] = $_GET['correo'];
}

// Obtener el correo electrónico del usuario de la sesión
$correoUsuario = isset($_SESSION['correo_usuario']) ? $_SESSION['correo_usuario'] : '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> <!-- Agregar Font Awesome para íconos -->
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2; /* Cambiar color de fondo */
        }

        .menu {
            background-color: #009688; /* Cambiar color de fondo */
            color: #fff;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            border: 2px solid #fff; /* Agregar borde */
            border-radius: 10px; /* Agregar bordes redondeados */
            margin: 10px; /* Añadir margen */
            z-index: 999; /* Asegura que el menú se muestre encima del contenido */
            position: auto;
        }

        .menu-logo {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            text-decoration: none;
        }

        .menu-toggle {
            display: none; /* Ocultar el botón de menú por defecto */
            color: #fff;
            font-size: 24px;
            cursor: pointer;
        }

        .menu-items {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-grow: 1; /* Permite que los elementos del menú ocupen todo el espacio disponible */
        }

        .menu-item {
            text-align: center;
            text-decoration: none;
            color: #fff;
            transition: transform 0.3s;
            padding: 10px 15px;
            display: flex;
            align-items: center;
            border-bottom: 2px solid transparent;
        }

        .menu-item i {
            font-size: 20px;
            margin-right: 8px;
        }

        .menu-item span {
            font-size: 16px;
            font-weight: bold;
        }

        .menu-item:hover {
            border-bottom: 2px solid #fff;
        }

        /* Estilos para pantallas pequeñas */
        @media screen and (max-width: 768px) {
            .menu {
                padding: 10px; /* Reducir el padding en pantallas pequeñas */
            }
            .menu-items {
                display: none; /* Ocultar los elementos del menú en pantallas pequeñas */
                position: fixed;
                top: 70px;
                left: 10px;
                right: 10px;
                background-color: #009688; /* Cambiar color de fondo */
                flex-direction: column;
                align-items: center;
                box-shadow: none;
                z-index: 999; /* Asegura que el menú se muestre encima del contenido */
                border-radius: 5px; /* Agregar bordes redondeados */
                padding: 10px;
            }
            .menu-item {
                margin: 10px 0;
                border-bottom: none;
                width: 100%;
            }
            .menu-item:hover {
                border-bottom: none;
            }
            .menu-item a {
                color: #fff; /* Cambiar color de enlaces */
                text-decoration: none; /* Quitar subrayado */
            }
            .menu-toggle {
                display: block; /* Mostrar el botón de menú en pantallas pequeñas */
            }
        }
    </style>
</head>
<body>

<div class="menu">
    <a href="#" class="menu-logo"><i class="fas fa-camera"></i> Galería</a> <!-- Agregar icono como logo -->
    <div class="menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </div>
    <div class="menu-items" id="menu-items">
        <a href="formularioVideos.php" class="menu-item">
            <i class="fas fa-file-image"></i>
            <span>Archivos</span>
        </a>
        <a href="formularioDocumento.php" class="menu-item">
            <i class="far fa-file-alt"></i>
            <span>Documentos</span>
        </a>
        <!-- Mostrar el correo electrónico del usuario y un mensaje -->
        <?php if (!empty($correoUsuario)) : ?>
            <div class="menu-item">
                <i class="fas fa-envelope"></i>
                <span><?php echo $correoUsuario; ?></span>
                <span class="usuario-conectado">(Usuario conectado)</span>
            </div>
        <?php endif; ?>
        <!-- Opción para salir -->
        <a href="salir.php" class="menu-item">
            <i class="fas fa-sign-out-alt"></i>
            <span>Salir</span>
        </a>
    </div>
</div>

<script>
    function toggleMenu() {
        var menuItems = document.getElementById('menu-items');
        menuItems.style.display = menuItems.style.display === 'flex' ? 'none' : 'flex';
    }

    // Cerrar el menú si se hace clic fuera de él
    // Cerrar el menú si se hace clic fuera de él en pantallas pequeñas
window.addEventListener('click', function(event) {
    // Verificar el tamaño de la pantalla
    if (window.innerWidth <= 768) {
        var menuItems = document.getElementById('menu-items');
        if (!event.target.closest('.menu') && !event.target.closest('.menu-toggle')) {
            menuItems.style.display = 'none';
        }
    }
});

</script>

</body>
</html>
