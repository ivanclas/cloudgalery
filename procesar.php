<?php
session_start();

// Verificar si se recibieron los datos del formulario por el método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibieron el correo electrónico y la contraseña
    if (isset($_POST["correo"]) && isset($_POST["contrasena"])) {
        // Recibir y limpiar los datos del formulario
        $correo = htmlspecialchars($_POST["correo"]);
        $contrasena = $_POST["contrasena"];

        // Conectar a la base de datos (suponiendo que ya tienes un archivo de conexión)
        require_once "conexion.php";

        // Consulta SQL para verificar las credenciales del usuario
        $sql = "SELECT idUsuario, clave FROM usuario WHERE correo = ? AND clave = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $correo, $contrasena);
        $stmt->execute();
        $stmt->store_result();

        // Verificar si se encontró un usuario con el correo electrónico y contraseña proporcionados
        if ($stmt->num_rows == 1) {
            // Vincular los resultados de la consulta
            $stmt->bind_result($id, $contrasena);

            // Contraseña correcta, iniciar sesión o realizar otras acciones necesarias
            $_SESSION["usuario_id"] = $id;

            // Redireccionar al usuario al menú con su correo electrónico como parámetro
            header("Location: inicio.php?correo=$correo");
            exit();
        } else {
            // Usuario no encontrado o contraseña incorrecta
            $_SESSION["error_message"] = "El correo electrónico o la contraseña son incorrectos. Por favor, inténtalo de nuevo.";
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conn->close();
    } else {
        // Datos del formulario incompletos
        $_SESSION["error_message"] = "Por favor, completa todos los campos.";
    }
} else {
    // Acceso incorrecto al archivo
    $_SESSION["error_message"] = "Acceso denegado.";
}

// Redireccionar al usuario a la página de inicio en caso de error
header("Location: index.php");
exit();
?>
