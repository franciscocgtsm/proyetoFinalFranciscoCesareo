<?php
$host = 'localhost';
$usuario = 'root';
$contrasena = '';
$bd = 'tienda_online';

$conn = new mysqli($host, $usuario, $contrasena, $bd);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer codificación de caracteres
$conn->set_charset("utf8");
?>
