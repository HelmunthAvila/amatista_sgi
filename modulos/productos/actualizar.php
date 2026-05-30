<?php
// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Recibe los datos enviados desde el formulario de edición de productos
$id       = $_POST['id'];

// Limpia los campos de texto para evitar inyección SQL
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$marca    = mysqli_real_escape_string($conexion, $_POST['marca']);
$talla    = mysqli_real_escape_string($conexion, $_POST['talla']);
$color    = mysqli_real_escape_string($conexion, $_POST['color']);

// Recibe valores numéricos del producto
$precio   = $_POST['precio'];
$stock    = $_POST['stock'];

// Consulta SQL para actualizar la información del producto según su ID
$sql = "UPDATE productos SET 
        nombre = '$nombre', 
        marca  = '$marca', 
        talla  = '$talla', 
        color  = '$color', 
        precio = '$precio', 
        stock  = '$stock' 
        WHERE id = $id";

// Ejecuta la consulta de actualización
if (mysqli_query($conexion, $sql)) {

    // Si la actualización es correcta redirige al listado de productos
    header("Location: listar.php?msj=actualizado");

} else {

    // Si ocurre un error muestra el mensaje técnico
    echo "Error al actualizar el producto: " . mysqli_error($conexion);

}
?>