<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Recepción y limpieza de datos para seguridad
$id       = $_POST['id'];
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$marca    = mysqli_real_escape_string($conexion, $_POST['marca']);
$talla    = mysqli_real_escape_string($conexion, $_POST['talla']);
$color    = mysqli_real_escape_string($conexion, $_POST['color']);
$precio   = $_POST['precio'];
$stock    = $_POST['stock'];

// 3. Sentencia SQL de actualización
$sql = "UPDATE productos SET 
        nombre = '$nombre', 
        marca  = '$marca', 
        talla  = '$talla', 
        color  = '$color', 
        precio = '$precio', 
        stock  = '$stock' 
        WHERE id = $id";

// 4. Ejecución y manejo de redirección
if (mysqli_query($conexion, $sql)) {
    // Regresa al listado con una señal de éxito
    header("Location: listar.php?msj=actualizado");
} else {
    // Mensaje en caso de error técnico
    echo "Error al actualizar el producto: " . mysqli_error($conexion);
}
?>