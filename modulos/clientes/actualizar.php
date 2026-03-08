<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Recepción de datos mediante POST
$id       = $_POST['id'];
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$email    = mysqli_real_escape_string($conexion, $_POST['email']);

// 3. Sentencia SQL para actualizar el registro específico
$sql = "UPDATE clientes SET 
        nombre = '$nombre', 
        telefono = '$telefono', 
        email = '$email' 
        WHERE id = $id";

// 4. Ejecución de la consulta
if (mysqli_query($conexion, $sql)) {
    // Redirigir al listado si la actualización fue exitosa
    header("Location: listar.php?msj=actualizado");
} else {
    // Mostrar error en caso de falla
    echo "Error al actualizar: " . mysqli_error($conexion);
}
?>