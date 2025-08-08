<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Debes iniciar sesión.");
}

$usuario_id = $_SESSION['usuario_id'];

// Obtener el carrito del usuario
$sql_carrito = "SELECT producto_id, cantidad FROM carrito WHERE usuario_id = ?";
$stmt_carrito = $conn->prepare($sql_carrito);
$stmt_carrito->bind_param("i", $usuario_id);
$stmt_carrito->execute();
$resultado_carrito = $stmt_carrito->get_result();

if ($resultado_carrito->num_rows === 0) {
    die("El carrito está vacío.");
}

// Calcular total
$total = 0;
$productos = [];

while ($item = $resultado_carrito->fetch_assoc()) {
    $producto_id = $item['producto_id'];
    $cantidad = $item['cantidad'];

    $sql_precio = "SELECT precio FROM productos WHERE id = ?";
    $stmt_precio = $conn->prepare($sql_precio);
    $stmt_precio->bind_param("i", $producto_id);
    $stmt_precio->execute();
    $stmt_precio->bind_result($precio_unitario);
    $stmt_precio->fetch();
    $stmt_precio->close();

    $subtotal = $precio_unitario * $cantidad;
    $total += $subtotal;

    $productos[] = [
        'producto_id' => $producto_id,
        'cantidad' => $cantidad,
        'precio_unitario' => $precio_unitario
    ];
}

// Insertar pedido
$sql_pedido = "INSERT INTO pedidos (usuario_id, total, estado, fecha) VALUES (?, ?, 'pendiente', NOW())";
$stmt_pedido = $conn->prepare($sql_pedido);
$stmt_pedido->bind_param("id", $usuario_id, $total);
$stmt_pedido->execute();
$pedido_id = $stmt_pedido->insert_id;
$stmt_pedido->close();

// Insertar detalles del pedido
$sql_detalle = "INSERT INTO detalles_pedido (pedido_id, producto_id, cantidad, precio_unitario) VALUES (?, ?, ?, ?)";
$stmt_detalle = $conn->prepare($sql_detalle);

foreach ($productos as $producto) {
    $stmt_detalle->bind_param(
        "iiid",
        $pedido_id,
        $producto['producto_id'],
        $producto['cantidad'],
        $producto['precio_unitario']
    );
    $stmt_detalle->execute();
}
$stmt_detalle->close();

// Vaciar el carrito
$sql_borrar = "DELETE FROM carrito WHERE usuario_id = ?";
$stmt_borrar = $conn->prepare($sql_borrar);
$stmt_borrar->bind_param("i", $usuario_id);
$stmt_borrar->execute();
$stmt_borrar->close();

$conn->close();

// Redirigir a la página de confirmación
header("Location: ../confirmacion.html");
exit();
?>
