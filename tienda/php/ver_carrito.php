<?php
session_start();
include 'conexion.php';

// Simulación de usuario logueado (en producción esto debería venir de $_SESSION)
$usuario_id = 1;

// Obtener los productos del carrito con detalles
$sql = "
SELECT 
    carrito.id AS carrito_id,
    productos.nombre,
    productos.precio,
    carrito.cantidad,
    (productos.precio * carrito.cantidad) AS total
FROM carrito
JOIN productos ON carrito.producto_id = productos.id
WHERE carrito.usuario_id = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();

$resultado = $stmt->get_result();

$total_general = 0;

echo "<h2 class='carrito-titulo'>🛒 Tu carrito</h2>";

if ($resultado->num_rows > 0) {
    echo "<table class='carrito-tabla'>";
    echo "<tr><th>Producto</th><th>Precio</th><th>Cantidad</th><th>Total</th></tr>";

    while ($fila = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
        echo "<td>" . number_format($fila['precio'], 2) . " €</td>";
        echo "<td>" . $fila['cantidad'] . "</td>";
        echo "<td>" . number_format($fila['total'], 2) . " €</td>";
        echo "</tr>";
        $total_general += $fila['total'];
    }

    echo "<tr class='carrito-total'><td colspan='3'><strong>Total general:</strong></td><td><strong>" . number_format($total_general, 2) . " €</strong></td></tr>";
    echo "</table>";

    // Botón de compra (simulado)
    echo "<form action='procesar_compra.php' method='POST' style='margin-top: 15px;'>";
    echo "<button type='submit' class='boton-comprar'>Finalizar Compra</button>";
    echo "</form>";
} else {
    echo "<p class='carrito-vacio'>Tu carrito está vacío.</p>";
}

$stmt->close();
$conn->close();
?>
