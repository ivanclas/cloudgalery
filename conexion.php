<?php
$servername = "localhost"; // Cambia localhost por el nombre del servidor si es necesario
$username = "root"; // Cambia tu_usuario por el nombre de usuario de tu base de datos
$password = ""; // Cambia tu_contraseña por la contraseña de tu base de datos
$dbname = "almacen"; // Cambia almacen por el nombre de tu base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
