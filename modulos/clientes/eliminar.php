<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Verificar que se haya recibido un ID válido
if (isset($_GET['id'])) {
    // Limpiamos el ID para evitar inyecciones SQL
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // 3. Ejecutar la sentencia de eliminación
    $sql = "DELETE FROM clientes WHERE id = '$id'";

    if (mysqli_query($conexion, $sql)) {
        // Si se elimina con éxito, volvemos al listado con un mensaje
        header("Location: listar.php?res=eliminado");
    } else {
        // Si hay un error (por ejemplo, si el cliente tiene ventas asociadas)
        echo "Error al eliminar el cliente: " . mysqli_error($conexion);
    }
} else {
    // Si intentan entrar sin un ID, los devolvemos al listado
    header("Location: listar.php");
}
?>