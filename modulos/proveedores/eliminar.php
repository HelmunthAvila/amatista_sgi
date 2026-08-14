<?php
include("../../includes/sesion.php");
// Iniciamos sesión para interactuar con las notificaciones

// Incluir el archivo de conexión a la base de datos
include("../../conexion.php");

// Verificar que el ID del proveedor fue enviado por la URL
if (isset($_GET['id'])) {

    // Limpiar el ID recibido
    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // Sentencia SQL para eliminar el proveedor
    $sql = "DELETE FROM proveedores WHERE id = '$id'";

    // Ejecutar la consulta de eliminación
    if (mysqli_query($conexion, $sql)) {
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensaje' => '<strong>¡Eliminado!</strong> El proveedor ha sido retirado del SGI de forma exitosa.'
        ];
    } else {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => '<strong>Error crítico:</strong> El proveedor no puede ser eliminado porque tiene productos o compras vinculadas en el inventario.'
        ];
    }
}

// Redirigir nuevamente al listado maestro
header("Location: listar.php");
exit();
?>