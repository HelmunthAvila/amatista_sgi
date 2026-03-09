<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Recepción y limpieza de datos
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$marca    = mysqli_real_escape_string($conexion, $_POST['marca']);
$talla    = mysqli_real_escape_string($conexion, $_POST['talla']);
$color    = mysqli_real_escape_string($conexion, $_POST['color']);
$precio   = $_POST['precio'];
$stock    = $_POST['stock'];

// 3. Sentencia SQL con las nuevas columnas de calzado
$sql = "INSERT INTO productos (nombre, marca, talla, color, precio, stock) 
        VALUES ('$nombre', '$marca', '$talla', '$color', '$precio', '$stock')";

// 4. Ejecución y redirección
if (mysqli_query($conexion, $sql)) {
    // Redirige al listado tras el éxito
    header("Location: listar.php?res=guardado");
} else {
    // En caso de error técnico
    echo "Error al guardar el producto: " . mysqli_error($conexion);
}
?>