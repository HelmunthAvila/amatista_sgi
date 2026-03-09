<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Validar que el ID no esté vacío
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // 3. Ejecutar la eliminación
    $query = mysqli_query($conexion, "DELETE FROM productos WHERE id = $id");

    if ($query) {
        // Redirigir con un mensaje de éxito (opcional)
        header("Location: listar.php?msj=eliminado");
    } else {
        // Manejo de error si el producto está ligado a una venta
        echo "Error: No se pudo eliminar el producto. " . mysqli_error($conexion);
    }
} else {
    header("Location: listar.php");
}
?>