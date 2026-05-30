<?php

// 1. Incluir el archivo de conexión a la base de datos
include("../../conexion.php");

// 2. Recepción de los datos enviados desde el formulario (POST)
// Se usa mysqli_real_escape_string para evitar inyección SQL
$nombre   = mysqli_real_escape_string($conexion, $_POST['nombre']);
$telefono = mysqli_real_escape_string($conexion, $_POST['telefono']);
$empresa  = mysqli_real_escape_string($conexion, $_POST['empresa']);

// 3. Sentencia SQL para insertar un nuevo proveedor en la tabla proveedores
$sql = "INSERT INTO proveedores (nombre, telefono, empresa) 
        VALUES ('$nombre', '$telefono', '$empresa')";

// 4. Ejecutar la consulta en la base de datos
if (mysqli_query($conexion, $sql)) {

    // Si el registro se guarda correctamente, redirigir al listado con mensaje de éxito
    header("Location: listar.php?res=exito");

} else {

    // Si ocurre un error en la consulta, mostrar el mensaje de error
    echo "Error al registrar el proveedor: " . mysqli_error($conexion);

}

?>