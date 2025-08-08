<?php
session_start();
include 'conexion.php';

// Simulación de login si no existe
if (!isset($_SESSION['usuario_id'])) {
    $_SESSION['usuario_id'] = 1; // para pruebas sin login real
}
$usuario_id = $_SESSION['usuario_id'];

// Verificación de datos POST
if (!isset($_POST['producto_id']) || !isset($_POST['cantidad'])) {
    die("Faltan datos del formulario.");
}

$producto_id = intval($_POST['producto_id']);
$cantidad = intval($_POST['cantidad']);

// Verificación lógica
if ($producto_id <= 0 || $cantidad <= 0) {
    die("Producto o cantidad inválidos.");
}

// SQL con actualización si ya existe
$sql = "INSERT INTO carrito (usuario_id, producto_id, cantidad)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE cantidad = cantidad + VALUES(cantidad)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $usuario_id, $producto_id, $cantidad);

if ($stmt->execute()) {
    // ✅ Redirige sin imprimir NADA antes
    header("Location: carrito.php?agregado=1");
    exit;
} else {
    die("Error al añadir al carrito: " . $stmt->error);
}

$stmt->close();
$conn->close();
?>
