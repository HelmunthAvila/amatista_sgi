<?php
// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Recibe los datos enviados desde el formulario de edición de proveedores
// Se limpian los valores para evitar inyección SQL
$id       = mysqli_real_escape_string($conexion, $_POST['id']);
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$empresa  = mysqli_real_escape_string($conexion, $_POST['empresa']);

// Consulta SQL para actualizar los datos del proveedor según su ID
$sql = "UPDATE proveedores SET 
        nombre = '$nombre', 
        telefono = '$telefono', 
        empresa = '$empresa' 
        WHERE id = '$id'";

// Ejecuta la consulta de actualización
if (mysqli_query($conexion, $sql)) {

    // Si la actualización es exitosa redirige al listado de proveedores
    header("Location: listar.php?res=actualizado");

} else {

    // Si ocurre un error muestra el mensaje técnico
    echo "Error al actualizar: " . mysqli_error($conexion);

}
?>