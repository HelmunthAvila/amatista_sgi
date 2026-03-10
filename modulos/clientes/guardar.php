<?php
// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Recibe y limpia los datos enviados desde el formulario agregar.php
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$email    = mysqli_real_escape_string($conexion, $_POST['email']);

// Consulta SQL para insertar un nuevo cliente en la tabla clientes
$sql = "INSERT INTO clientes (nombre, telefono, email) 
        VALUES ('$nombre', '$telefono', '$email')";

// Ejecuta la consulta de inserción en la base de datos
if (mysqli_query($conexion, $sql)) {

    // Redirige al listado de clientes mostrando mensaje de registro exitoso
    header("Location: listar.php?res=exito");

} else {

    // Muestra el error si ocurre algún problema al guardar el cliente
    echo "Error al registrar cliente: " . mysqli_error($conexion);

}
?>