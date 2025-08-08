<!-- registro.php -->
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'conexion.php';

$nombre = $_POST['nombre'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$rol = $_POST['rol'];

$sql = "INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssi", $nombre, $email, $password, $rol);

if ($stmt->execute()) {
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <meta charset='UTF-8'>
        <title>Registro Exitoso</title>
        <link rel='stylesheet' href='../css/estilos.css'>
        <meta http-equiv='refresh' content='3;url=../login.html'>
    </head>
    <body>
        <h2>Registro completado</h2>
        <p>Serás redirigido al login en 3 segundos...</p>
        <p><a href='../login.html'>Haz clic aquí si no eres redirigido automáticamente</a></p>
    </body>
    </html>";
} else {
    echo "Error: " . $stmt->error;
}
$conn->close();
