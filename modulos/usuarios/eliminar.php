<?php
include("../../includes/sesion.php");
requiere_rol('admin');

// 1. Incluimos la conexión a la base de datos usando la ruta correcta
include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: listar.php");
    exit();
}

// 2. Validamos que el ID haya sido enviado y que no esté vacío
if (isset($_POST['id']) && !empty($_POST['id'])) {

    // El motivo es obligatorio para la trazabilidad de auditoría
    $motivo = trim($_POST['motivo'] ?? '');
    if ($motivo === '') {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Debe indicar el motivo de la desactivación.'];
        header("Location: listar.php");
        exit();
    }

    $id_usuario = intval($_POST['id']);
    $id_actual = intval($_SESSION['id_usuario']);

    // No permitir desactivarse a sí mismo
    if ($id_usuario === $id_actual) {
        $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'No puedes desactivar tu propio usuario.'];
        header("Location: listar.php");
        exit();
    }

    // 3. Soft delete: se desactiva el acceso conservando el registro (auditoría)
    $stmt = mysqli_prepare($conexion, "UPDATE usuarios SET estado = 0, eliminado_por = ?, eliminado_en = NOW(), motivo_eliminacion = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "isi", $id_actual, $motivo, $id_usuario);
    $resultado = mysqli_stmt_execute($stmt);

    if ($resultado) {
        // Si todo sale bien, redirige a la lista
        header("Location: listar.php?status=deleted");
        exit();
    } else {
        // Si hay error en la query de la base de datos
        echo "Error al intentar desactivar el usuario. Inténtalo de nuevo.";
    }

} else {
    // Si intentan entrar a la mala sin pasar un ID en la URL, los mandamos de regreso
    header("Location: listar.php");
    exit();
}
?>
