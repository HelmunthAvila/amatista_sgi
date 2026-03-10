<?php
// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Recibe los datos enviados desde el formulario agregar.php
// Se limpian los campos de texto para evitar inyección SQL
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$marca    = mysqli_real_escape_string($conexion, $_POST['marca']);
$talla    = mysqli_real_escape_string($conexion, $_POST['talla']);
$color    = mysqli_real_escape_string($conexion, $_POST['color']);

// Recibe los valores numéricos del producto
$precio   = $_POST['precio'];
$stock    = $_POST['stock'];

// Consulta SQL para insertar un nuevo producto en la tabla productos
$sql = "INSERT INTO productos (nombre, marca, talla, color, precio, stock) 
        VALUES ('$nombre', '$marca', '$talla', '$color', '$precio', '$stock')";

// Ejecuta la consulta y verifica si el registro fue exitoso
if (mysqli_query($conexion, $sql)) {

    // Si se guarda correctamente redirige al listado de productos
    header("Location: listar.php?res=guardado");

} else {

    // Si ocurre un error muestra el mensaje técnico
    echo "Error al guardar el producto: " . mysqli_error($conexion);

}
?>