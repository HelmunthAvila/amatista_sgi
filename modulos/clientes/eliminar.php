<?php
include("../../includes/sesion.php");
requiere_rol('admin');
// Iniciamos sesión para interactuar con las notificaciones

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}

// Verifica que se haya recibido el ID del cliente
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

    // Soft delete: el cliente queda desactivado con registro de quién, cuándo y por qué (auditoría)
    $stmt = mysqli_prepare($conexion, "UPDATE clientes SET estado = 0, eliminado_por = ?, eliminado_en = NOW(), motivo_eliminacion = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "isi", $id_usuario, $motivo, $id);

    // Ejecuta la consulta de desactivación
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['alerta'] = [
            'tipo' => 'success',
            'mensaje' => '<strong>¡Desactivado!</strong> El cliente fue deshabilitado y su historial se conserva.'
        ];
    } else {
        $_SESSION['alerta'] = [
            'tipo' => 'danger',
            'mensaje' => '<strong>Error:</strong> No se pudo desactivar el cliente.'
        ];
    }
}

// Redirige siempre al listado
header("Location: listar.php");
exit();
?>
