<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión para mandar mensajes fluidos

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}


// Verifica que se haya recibido el ID del producto por la URL
if (isset($_POST['id'])) {

    // Limpia el ID recibido para evitar inyección SQL
    $id = mysqli_real_escape_string($conexion, $_POST['id']);

    // Consultamos el nombre antes de eliminarlo para el mensaje informativo personalizado
    $buscar_producto = mysqli_query($conexion, "SELECT nombre FROM productos WHERE id = $id");
    $resultado = mysqli_fetch_assoc($buscar_producto);
    $nombre_producto = $resultado ? $resultado['nombre'] : 'Desconocido';

    // Ejecuta la consulta SQL para eliminar el producto
    $query = mysqli_query($conexion, "DELETE FROM productos WHERE id = $id");

    // Verifica si la eliminación fue exitosa e inyecta la alerta en sesión
    if ($query) {
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensaje' => '<strong>¡Producto eliminado!</strong> El modelo <strong>"' . htmlspecialchars($nombre_producto) . '"</strong> fue removido del inventario.'
        ];
    } else {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => '<strong>Error al eliminar:</strong> El modelo <strong>"' . htmlspecialchars($nombre_producto) . '"</strong> tiene facturas asociadas en el POS y no se puede borrar.'
        ];
    }

} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se especificó un ID de producto válido.'
    ];
}

// Redirección al módulo maestro
header("Location: listar.php");
exit();
?>