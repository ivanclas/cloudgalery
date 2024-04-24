<?php
session_start();

// Verificar si existe un mensaje de error
if (isset($_SESSION["error_message"])) {
    $error_message = $_SESSION["error_message"];
    unset($_SESSION["error_message"]); // Limpiar el mensaje de error para futuras solicitudes
} else {
    $error_message = ""; // Inicializar el mensaje de error
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .login-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .login-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .login-container .register-link {
            color: #007bff;
            text-decoration: none;
        }

        .login-container .register-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Iniciar Sesión</h2>
    <!-- Mostrar el mensaje de error si existe -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form action="procesar.php" method="post" id="loginForm">
        <input type="email" name="correo" id="correo" placeholder="Correo Electrónico" required>
        <br>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <br>
        <input type="submit" value="Iniciar Sesión">
    </form>
    <p>¿No tienes una cuenta? <a href="registro.php" id="registerLink" class="register-link">Regístrate aquí</a></p>
</div>

<script>
    // Obtener el formulario de inicio de sesión y el campo de correo electrónico
    const loginForm = document.getElementById('loginForm');
    const correoInput = document.getElementById('correo');

    // Agregar un evento 'submit' al formulario
    loginForm.addEventListener('submit', function(event) {
        // Obtener el valor del correo electrónico ingresado
        const correo = correoInput.value;

        // Construir la URL del enlace de registro con el correo electrónico como parámetro
        const registerLink = document.getElementById('registerLink');
        registerLink.href = `registro.php?correo=${correo}`;
    });
</script>

</body>
</html>
