<?php
include 'conexion.php';
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);

$html = "";
if ($resultado->num_rows > 0) {
    while ($prod = $resultado->fetch_assoc()) {
        
        $html .= "<div class='producto'>";
        $html .= "<img src='img/" . $prod['imagen'] . "' alt='" . $prod['nombre'] . "'>";
        $html .= "<h3>" . $prod['nombre'] . "</h3>";
        $html .= "<p>" . $prod['descripcion'] . "</p>";
        $html .= "<p><strong>" . number_format($prod['precio'], 2) . " €</strong></p>";
        $html .= "<form action='php/agregar_carrito.php' method='POST'>";
        $html .= "<input type='hidden' name='producto_id' value='" . $prod['id'] . "'>";
        $html .= "<input type='hidden' name='cantidad' value='1'>";
        $html .= "<button type='submit'>Añadir al carrito</button>";
        $html .= "</form>";
        $html .= "</div>";
    }
} else {
    $html .= "<p>No hay productos disponibles.</p>";
}

$conn->close();
echo $html;
?>
