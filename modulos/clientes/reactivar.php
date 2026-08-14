<?php
include("../../includes/sesion.php");
requiere_rol('admin');

include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);

    // Reactivar: restaura el estado activo y limpia los datos de auditoría de la desactivación
    $stmt = mysqli_prepare($conexion, "UPDATE clientes SET estado = 1, eliminado_por = NULL, eliminado_en = NULL, motivo_eliminacion = NULL WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => '<strong>¡Reactivado!</strong> El cliente volvió a estar activo.'];
    } else {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => '<strong>Error:</strong> No se pudo reactivar el cliente.'];
    }
    mysqli_stmt_close($stmt);
}

header("Location: listar.php");
exit();
?>
