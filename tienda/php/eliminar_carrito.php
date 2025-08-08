<?php
session_start();
include 'conexion.php';

if (!isset($_POST['carrito_id'])) {
    die("ID del carrito no recibido.");
}

$carrito_id = intval($_POST['carrito_id']);
$usuario_id = $_SESSION['usuario_id'] ?? 1;

// Solo elimina si pertenece al usuario
$sql = "DELETE FROM carrito WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $carrito_id, $usuario_id);

if ($stmt->execute()) {
    header("Location: carrito.php");
    exit;
} else {
    echo "Error al eliminar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
