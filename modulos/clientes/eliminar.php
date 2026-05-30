<?php
// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Verifica que se haya recibido el ID del cliente mediante la URL
if (isset($_GET['id'])) {

    // Limpia el valor del ID para evitar inyecciones SQL
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // Consulta SQL para eliminar el cliente seleccionado
    $sql = "DELETE FROM clientes WHERE id = '$id'";

    // Ejecuta la consulta de eliminación
    if (mysqli_query($conexion, $sql)) {

        // Redirige al listado mostrando mensaje de eliminación exitosa
        header("Location: listar.php?res=eliminado");

    } else {

        // Muestra el error si la eliminación falla (por ejemplo, por relaciones en la base de datos)
        echo "Error al eliminar el cliente: " . mysqli_error($conexion);

    }

} else {

    // Redirige al listado si alguien intenta acceder al archivo sin enviar un ID
    header("Location: listar.php");

}
?>