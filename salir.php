<?php
// Iniciar la sesión si no está iniciada
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Borrar la cookie de sesión si existe
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión (o cualquier otra página)
header("Location: index.php");
exit;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrando sesión</title>
    <script>
        // Mostrar un mensaje de alerta al usuario antes de salir
        window.onload = function() {
            var confirmLogout = confirm("¿Estás seguro de que quieres cerrar sesión?");
            if (confirmLogout) {
                window.location.href = "index.php"; // Redirigir al usuario si confirma
            } else {
                window.location.href = "menu.php"; // Redirigir al menú si cancela
            }
        };
    </script>
</head>
<body>
</body>
</html>
