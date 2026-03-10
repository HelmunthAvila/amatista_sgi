<?php

// 1. Incluir el archivo de conexión a la base de datos
include("../../conexion.php");

// 2. Verificar que el ID del proveedor fue enviado por la URL
if (isset($_GET['id'])) {

    // Limpiar el ID recibido para evitar inyección SQL
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // 3. Sentencia SQL para eliminar el proveedor de la tabla proveedores
    $sql = "DELETE FROM proveedores WHERE id = '$id'";

    // 4. Ejecutar la consulta de eliminación
    if (mysqli_query($conexion, $sql)) {

        // Si la eliminación fue exitosa, redirige al listado con mensaje de confirmación
        header("Location: listar.php?msg=eliminado");

    } else {

        // Si ocurre un error (por ejemplo relaciones con otras tablas), mostrar mensaje
        echo "Error al eliminar: " . mysqli_error($conexion);

    }

} else {

    // 5. Si no se recibe el ID, redirigir nuevamente al listado
    header("Location: listar.php");

}

?>