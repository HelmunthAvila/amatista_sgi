<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Recepción de datos y limpieza para seguridad
$id       = mysqli_real_escape_string($conexion, $_POST['id']);
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$empresa  = mysqli_real_escape_string($conexion, $_POST['empresa']);

// 3. Sentencia SQL para actualizar el registro específico
$sql = "UPDATE proveedores SET 
        nombre = '$nombre', 
        telefono = '$telefono', 
        empresa = '$empresa' 
        WHERE id = '$id'";

// 4. Ejecución y redirección
if (mysqli_query($conexion, $sql)) {
    // Regresa al listado con un parámetro de éxito
    header("Location: listar.php?res=actualizado");
} else {
    // En caso de error técnico
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>