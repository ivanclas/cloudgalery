<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
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

        .register-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
        }

        .register-container h2 {
            margin-bottom: 20px;
            color: #333;
        }

        .register-container input[type="text"],
        .register-container input[type="email"],
        .register-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .register-container input[type="submit"] {
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

        .register-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .register-container .login-link {
            color: #007bff;
            text-decoration: none;
        }

        .register-container .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <h2>Registro de Usuario</h2>
    <form action="procesarRegistro.php" method="post">
        <input type="text" name="nombre" placeholder="Nombre" required>
        <br>
        <input type="email" name="correo" placeholder="Correo Electrónico" required>
        <br>
        <input type="password" name="contrasena" placeholder="Contraseña" required>
        <br>
        <input type="submit" value="Registrarse">
    </form>
    <p>¿Ya tienes una cuenta? <a href="index.php" class="login-link">Inicia sesión aquí</a></p>
</div>

</body>
</html>
