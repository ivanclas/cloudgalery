<?php
// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibieron todos los campos del formulario
    if (isset($_POST["nombre"]) && isset($_POST["correo"]) && isset($_POST["contrasena"])) {
        // Recibir y limpiar los datos del formulario
        $nombre = $_POST["nombre"];
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];

        // Conectar a la base de datos (suponiendo que ya tienes un archivo de conexión)
        require_once "conexion.php";

        // Verificar si el correo electrónico ya está registrado
        $sql = "SELECT idUsuario FROM usuario WHERE correo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        // Si el correo electrónico no está registrado, proceder con el registro
        if ($stmt->num_rows == 0) {
            // Hash de la contraseña para almacenarla de forma segura en la base de datos
            $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

            // Insertar el nuevo usuario en la base de datos
            $sql_insert = "INSERT INTO usuario (nombre, correo, clave) VALUES (?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bind_param("sss", $nombre, $correo, $contrasena);

            if ($stmt_insert->execute()) {
                // Registro exitoso, redireccionar al usuario a la página de inicio de sesión
                header("Location: index.php");
                exit();
            } else {
                // Error al registrar el usuario
                echo "Ocurrió un error al registrar el usuario. Por favor, inténtalo de nuevo.";
            }
        } else {
            // El correo electrónico ya está registrado
            echo "El correo electrónico proporcionado ya está registrado. Por favor, utiliza otro correo electrónico.";
        }

        // Cerrar la conexión a la base de datos
        $stmt->close();
        $conn->close();
    } else {
        // Datos del formulario incompletos
        echo "Por favor, completa todos los campos.";
    }
} else {
    // Acceso incorrecto al archivo
    echo "Acceso denegado.";
}
?>
