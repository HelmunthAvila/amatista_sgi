<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Recepción de datos desde el formulario (agregar.php)
// Usamos mysqli_real_escape_string por seguridad
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$email    = mysqli_real_escape_string($conexion, $_POST['email']);

// 3. Sentencia SQL para insertar el nuevo cliente
$sql = "INSERT INTO clientes (nombre, telefono, email) 
        VALUES ('$nombre', '$telefono', '$email')";

// 4. Ejecución de la consulta
if (mysqli_query($conexion, $sql)) {
    // Si se guarda correctamente, redirigir al listado moderno
    header("Location: listar.php?res=exito");
} else {
    // En caso de error, mostrar el fallo técnico
    echo "Error al registrar cliente: " . mysqli_error($conexion);
}
?>