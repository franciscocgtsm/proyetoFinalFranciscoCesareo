<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    die("Debes iniciar sesión para ver tus pedidos.");
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT * FROM pedidos WHERE usuario_id = ? ORDER BY fecha DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

$pedidos = [];

while ($pedido = $resultado->fetch_assoc()) {
    $sql_detalles = "SELECT dp.*, p.nombre 
                     FROM detalles_pedido dp 
                     JOIN productos p ON dp.producto_id = p.id 
                     WHERE dp.pedido_id = ?";
    $stmt_detalles = $conn->prepare($sql_detalles);
    $stmt_detalles->bind_param("i", $pedido['id']);
    $stmt_detalles->execute();
    $detalles = $stmt_detalles->get_result()->fetch_all(MYSQLI_ASSOC);

    $pedido['detalles'] = $detalles;
    $pedidos[] = $pedido;
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mis Pedidos - Tienda Online</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        .pedido {
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .pedido h3 {
            margin-top: 0;
        }
        .detalle {
            margin-left: 20px;
        }
        .pedido ul {
            list-style: square;
            padding-left: 20px;
        }
    </style>
</head>
<body>
<header>
    <div class="logo-container">
        <img src="../img/logo.png" alt="Logo Tienda" class="logo-img">
    </div>
    <h1>Tienda Online</h1>
    <nav>
        <ul>
            <li><a href="../registro.html">Registro</a></li>
            <li><a href="../login.html">Login</a></li>
            <li><a href="../catalogo.html">Catálogo</a></li>
            <li><a href="carrito.php">Carrito</a></li>
            <li><a href="pedidos.php">Mis Pedidos</a></li>
        </ul>
    </nav>
</header>

<main>
    <h2>Historial de Pedidos</h2>
    <?php if (count($pedidos) === 0): ?>
        <p>No tienes pedidos registrados.</p>
    <?php else: ?>
        <?php foreach ($pedidos as $pedido): ?>
            <div class="pedido">
                <h3>Pedido #<?= $pedido['id'] ?></h3>
                <p><strong>Fecha:</strong> <?= $pedido['fecha'] ?></p>
                <p><strong>Total:</strong> <?= number_format($pedido['total'], 2) ?> €</p>
                <p><strong>Estado:</strong> <?= $pedido['estado'] ?></p>
                <div class="detalle">
                    <strong>Detalles:</strong>
                    <ul>
                        <?php foreach ($pedido['detalles'] as $detalle): ?>
                            <li><?= $detalle['cantidad'] ?> x <?= $detalle['nombre'] ?> (<?= number_format($detalle['precio_unitario'], 2) ?> € c/u)</li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<footer>
    <p>&copy; 2025 Francisco Cesáreo Mayoral Ramiro. Todos los derechos reservados.</p>
    <img src="../img/derechos_autor.png" alt="Derechos de autor" style="position: fixed; right: 10px; bottom: 10px; width: 100px;">
</footer>
</body>
</html>
