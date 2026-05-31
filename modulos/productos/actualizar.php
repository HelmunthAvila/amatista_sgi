<?php
// Inicia la sesión para almacenar el estado de la alerta corporativa
session_start();

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

// Ejecuta la consulta de actualización y define alertas de sesión estilo Amatista SGI
if (mysqli_query($conexion, $sql)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Producto modificado!</strong> Los cambios en el modelo <strong>"' . $nombre . '"</strong> fueron guardados con éxito.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error al modificar:</strong> No se guardaron los cambios. ' . mysqli_error($conexion)
    ];
}

// Redirige al inventario limpio de parámetros URL
header("Location: listar.php");
exit();
?>