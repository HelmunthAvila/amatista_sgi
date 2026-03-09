<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Recepción y limpieza de datos para evitar errores de SQL
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$empresa  = mysqli_real_escape_string($conexion, $_POST['empresa']);

// 3. Sentencia SQL con las columnas de tu tabla proveedores
$sql = "INSERT INTO proveedores (nombre, telefono, empresa) 
        VALUES ('$nombre', '$telefono', '$empresa')";

// 4. Ejecución y redirección
if (mysqli_query($conexion, $sql)) {
    // Redirige al listado tras guardar con éxito
    header("Location: listar.php?res=exito");
} else {
    // Mensaje de error en caso de fallo técnico
    echo "Error al registrar el proveedor: " . mysqli_error($conexion);
}
?>