<!-- checkout.php -->
<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.html');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$direccion = $_POST['direccion'] ?? '';
$metodo_pago = $_POST['metodo_pago'] ?? '';

// Obtener el contenido del carrito
$sql = "SELECT c.producto_id, c.cantidad, p.precio FROM carrito c JOIN productos p ON c.producto_id = p.id WHERE c.usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

$productos = [];
$total = 0;
while ($fila = $resultado->fetch_assoc()) {
    $productos[] = $fila;
    $total += $fila['precio'] * $fila['cantidad'];
}

if (empty($productos)) {
    echo "<p>El carrito está vacío.</p>";
    exit();
}

// Insertar pedido
$sql_pedido = "INSERT INTO pedidos (usuario_id, total, estado, fecha) VALUES (?, ?, 'pendiente', NOW())";
$stmt_pedido = $conn->prepare($sql_pedido);
$stmt_pedido->bind_param('id', $usuario_id, $total);
$stmt_pedido->execute();
$pedido_id = $stmt_pedido->insert_id;

// Insertar detalles del pedido
$sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
$stmt_detalle = $conn->prepare($sql_detalle);

foreach ($productos as $prod) {
    $stmt_detalle->bind_param('iiid', $pedido_id, $prod['producto_id'], $prod['cantidad'], $prod['precio']);
    $stmt_detalle->execute();

    // Actualizar stock
    $sql_stock = "UPDATE productos SET stock = stock - ? WHERE id = ?";
    $stmt_stock = $conn->prepare($sql_stock);
    $stmt_stock->bind_param('ii', $prod['cantidad'], $prod['producto_id']);
    $stmt_stock->execute();
}

// Insertar en pagos
$sql_pago = "INSERT INTO pagos (pedido_id, metodo_pago, total_pago, estado, fecha) VALUES (?, ?, ?, 'pagado', NOW())";
$stmt_pago = $conn->prepare($sql_pago);
$stmt_pago->bind_param('isd', $pedido_id, $metodo_pago, $total);
$stmt_pago->execute();

// Vaciar carrito
$conn->query("DELETE FROM carrito WHERE usuario_id = $usuario_id");

// Redirigir a confirmación
header('Location: ../confirmacion.html');
exit();
?>
