<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Capturar el ID desde la URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // 3. Ejecutar la eliminación
    $sql = "DELETE FROM proveedores WHERE id = '$id'";

    if (mysqli_query($conexion, $sql)) {
        // Redirección con éxito
        header("Location: listar.php?msg=eliminado");
    } else {
        // Manejo de error (por ejemplo, si tiene productos asociados)
        echo "Error al eliminar: " . mysqli_error($conexion);
    }
} else {
    // Si no hay ID, volver al listado
    header("Location: listar.php");
}
?>