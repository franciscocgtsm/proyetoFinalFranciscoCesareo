<?php
$host = 'db.ffitpmolvwcxuwrokwfu.supabase.co';
$puerto = '5432';
$usuario = 'postgres';
$contrasena = 'Gta56789+'; 
$bd = 'postgres';

try {
    $dsn = "pgsql:host=$host;port=$puerto;dbname=$bd;";
    $conexion = new PDO($dsn, $usuario, $contrasena, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
    echo "✅ Conexión exitosa.";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
