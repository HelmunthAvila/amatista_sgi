<?php
// Iniciamos sesión para interactuar con el sistema de notificaciones
session_start();

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Recibe los datos enviados desde el formulario de edición de proveedores
$id       = intval($_POST['id']);
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$empresa  = mysqli_real_escape_string($conexion, $_POST['empresa']);

// Consulta SQL para actualizar los datos del proveedor según su ID
$sql = "UPDATE proveedores SET 
        nombre = '$nombre', 
        telefono = '$telefono', 
        empresa = '$empresa' 
        WHERE id = $id";

// Ejecuta la consulta de actualización
if (mysqli_query($conexion, $sql)) {
    $_SESSION['alerta'] = [
        'tipo' => 'success',
        'mensaje' => '<strong>¡Actualizado!</strong> Los datos del proveedor se modificaron con éxito.'
    ];
} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se pudo actualizar la información del proveedor. ' . mysqli_error($conexion)
    ];
}

// Redirige al listado sin ensuciar la URL
header("Location: listar.php");
exit();
?>