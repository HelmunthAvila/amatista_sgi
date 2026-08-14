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

// Verifica que se haya recibido el ID del producto
if (isset($_POST['id'])) {

    // El motivo es obligatorio para la trazabilidad de auditoría
    $motivo = trim($_POST['motivo'] ?? '');
    if ($motivo === '') {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Debe indicar el motivo de la desactivación.'];
        header("Location: listar.php");
        exit();
    }

    $id = intval($_POST['id']);
    $id_usuario = intval($_SESSION['id_usuario']);

    // Consultamos el nombre antes de desactivarlo para el mensaje informativo personalizado
    $stmt_nombre = mysqli_prepare($conexion, "SELECT nombre FROM productos WHERE id = ?");
    mysqli_stmt_bind_param($stmt_nombre, "i", $id);
    mysqli_stmt_execute($stmt_nombre);
    $resultado = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_nombre));
    $nombre_producto = $resultado ? $resultado['nombre'] : 'Desconocido';
    mysqli_stmt_close($stmt_nombre);

    // Soft delete: el producto queda desactivado con registro de quién, cuándo y por qué (auditoría).
    // No se borra físicamente para conservar la integridad referencial con las ventas pasadas.
    $stmt = mysqli_prepare($conexion, "UPDATE productos SET estado = 0, eliminado_por = ?, eliminado_en = NOW(), motivo_eliminacion = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "isi", $id_usuario, $motivo, $id);

    // Verifica si la desactivación fue exitosa e inyecta la alerta en sesión
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensaje' => '<strong>¡Producto desactivado!</strong> El modelo <strong>"' . htmlspecialchars($nombre_producto) . '"</strong> fue deshabilitado y su historial se conserva.'
        ];
    } else {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => '<strong>Error al desactivar:</strong> No se pudo deshabilitar el modelo <strong>"' . htmlspecialchars($nombre_producto) . '"</strong>.'
        ];
    }

} else {
    $_SESSION['alerta'] = [
        'tipo' => 'danger',
        'mensaje' => '<strong>Error:</strong> No se especificó un ID de producto válido.'
    ];
}
mysqli_stmt_close($stmt);

// Redirección al módulo maestro
header("Location: listar.php");
exit();
?>
