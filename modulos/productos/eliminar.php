<?php
// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Verifica que se haya recibido el ID del producto por la URL
if (isset($_GET['id'])) {

    // Limpia el ID recibido para evitar inyección SQL
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // Ejecuta la consulta SQL para eliminar el producto de la tabla productos
    $query = mysqli_query($conexion, "DELETE FROM productos WHERE id = $id");

    // Verifica si la eliminación fue exitosa
    if ($query) {

        // Redirige al listado de productos mostrando mensaje de eliminación exitosa
        header("Location: listar.php?msj=eliminado");

    } else {

        // Muestra error si el producto no se puede eliminar (por ejemplo si está relacionado con ventas)
        echo "Error: No se pudo eliminar el producto. " . mysqli_error($conexion);

    }

} else {

    // Si no se envía un ID válido, redirige nuevamente al listado
    header("Location: listar.php");

}
?>