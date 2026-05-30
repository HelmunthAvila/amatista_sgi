<?php

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Recibe los datos enviados desde el formulario mediante POST
$id       = $_POST['id'];
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$email    = mysqli_real_escape_string($conexion, $_POST['email']);

// Consulta SQL para actualizar los datos del cliente seleccionado
$sql = "UPDATE clientes SET 
        nombre = '$nombre', 
        telefono = '$telefono', 
        email = '$email' 
        WHERE id = $id";

// Ejecuta la consulta en la base de datos
if (mysqli_query($conexion, $sql)) {

    // Redirige al listado de clientes mostrando mensaje de actualización exitosa
    header("Location: listar.php?msj=actualizado");

} else {

    // Muestra el error de MySQL si ocurre algún problema en la actualización
    echo "Error al actualizar: " . mysqli_error($conexion);

}

?>