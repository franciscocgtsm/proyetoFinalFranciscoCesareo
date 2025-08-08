<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    echo "<p>Debes iniciar sesión para ver el carrito.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carrito</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body {
            padding-bottom: 130px; /* evita que el contenido se solape con la imagen fija */
        }

        footer img {
            position: fixed;
            right: 10px;
            bottom: 10px;
            width: 100px;
            z-index: 999;
        }

        .logo-container {
            display: inline-block;
            vertical-align: middle;
        }

        .logo-img {
            width: 60px;
            height: auto;
        }

        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 15px;
        }

        nav li {
            display: inline;
        }

        nav a {
            text-decoration: none;
            color: black;
        }

        .carrito-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            gap: 10px;
        }

        .carrito-item img {
            width: 80px;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <img src="../img/logo.png" class="logo-img">
        </div>
        <h1>Tienda Online</h1>
        <nav>
            <ul>
                <li><a href="../index.html">Inicio</a></li>
                <li><a href="../registro.html">Registro</a></li>
                <li><a href="../login.html">Login</a></li>
                <li><a href="../catalogo.html">Catálogo</a></li>
                <li><a href="carrito.php">Carrito</a></li>
                <li><a href="pedidos.php">Mis Pedidos</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <?php
        if (isset($_GET['agregado'])) {
            echo "<p style='color: green;'>✅ Producto añadido al carrito.</p>";
        }

        $usuario_id = $_SESSION['usuario_id'];
        $sql = "SELECT c.id AS carrito_id, p.nombre, p.precio, p.imagen, c.cantidad, (p.precio * c.cantidad) AS total
                FROM carrito c 
                JOIN productos p ON c.producto_id = p.id 
                WHERE c.usuario_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        $total_general = 0;

        echo "<h2>Mi Carrito</h2><div class='carrito-lista'>";

        while ($item = $resultado->fetch_assoc()) {
            $total_general += $item['total'];

            echo "<div class='carrito-item'>";
            echo "<img src='../img/{$item['imagen']}' alt='{$item['nombre']}'>";
            echo "<div style='flex-grow: 1;'>";
            echo "<p><strong>{$item['nombre']}</strong><br> {$item['cantidad']} x {$item['precio']} € = {$item['total']} €</p>";
            echo "</div>";
            echo "<form action='eliminar_carrito.php' method='POST'>";
            echo "<input type='hidden' name='carrito_id' value='{$item['carrito_id']}'>";
            echo "<button type='submit'>Eliminar</button>";
            echo "</form>";
            echo "</div>";
        }

        echo "<hr><p><strong>Total: {$total_general} €</strong></p>";

        echo "<form action='procesar_compra.php' method='POST'>";
        echo "<button type='submit'>Comprar</button>";
        echo "</form>";

        echo "</div>";

        $stmt->close();
        $conn->close();
        ?>
    </main>

    <footer>
        <p>&copy; 2025 Francisco Cesáreo Mayoral Ramiro. Todos los derechos reservados.</p>
        <img src="../img/derechos_autor.png" alt="Derechos de Autor">
    </footer>
</body>
</html>
