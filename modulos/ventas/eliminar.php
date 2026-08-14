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

if (!isset($_POST['id']) || empty($_POST['id'])) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'No se especificó un ID de venta válido para anular.'];
    header("Location: listar.php");
    exit();
}

// El motivo es obligatorio para la trazabilidad de auditoría
$motivo = trim($_POST['motivo'] ?? '');
if ($motivo === '') {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Debe indicar el motivo de la anulación.'];
    header("Location: listar.php");
    exit();
}

$id_venta = intval($_POST['id']);
$id_usuario = intval($_SESSION['id_usuario']);

mysqli_begin_transaction($conexion);

try {
    // Verificar que la venta exista y esté activa
    $stmt_verif = mysqli_prepare($conexion, "SELECT id FROM ventas WHERE id = ? AND estado = 1");
    mysqli_stmt_bind_param($stmt_verif, "i", $id_venta);
    mysqli_stmt_execute($stmt_verif);
    $venta_valida = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_verif));
    mysqli_stmt_close($stmt_verif);

    if (!$venta_valida) {
        throw new Exception("La venta no existe o ya fue anulada.");
    }

    // Consultas preparadas (AM-005): restaurar stock y marcar la venta como anulada (auditoría)
    $stmt_detalle = mysqli_prepare($conexion, "SELECT id_producto, cantidad FROM detalle_venta WHERE id_venta = ?");
    mysqli_stmt_bind_param($stmt_detalle, "i", $id_venta);
    mysqli_stmt_execute($stmt_detalle);
    $query_detalle = mysqli_stmt_get_result($stmt_detalle);

    if (!$query_detalle) {
        throw new Exception("Error al consultar el desglose de productos.");
    }

    while ($d = mysqli_fetch_assoc($query_detalle)) {
        $id_producto = $d['id_producto'];
        $cantidad = $d['cantidad'];

        $stmt_stock = mysqli_prepare($conexion, "UPDATE productos SET stock = stock + ? WHERE id = ? AND estado = 1");
        mysqli_stmt_bind_param($stmt_stock, "ii", $cantidad, $id_producto);
        $resultado_stock = mysqli_stmt_execute($stmt_stock);
        mysqli_stmt_close($stmt_stock);

        if (!$resultado_stock) {
            throw new Exception("Error al restaurar las existencias en inventarios.");
        }
    }
    mysqli_stmt_close($stmt_detalle);

    // Soft delete: la venta queda registrada como anulada (quién, cuándo y por qué)
    $stmt_anula = mysqli_prepare($conexion, "UPDATE ventas SET estado = 0, anulada_por = ?, anulada_en = NOW(), motivo_anulacion = ? WHERE id = ?");
    mysqli_stmt_bind_param($stmt_anula, "isi", $id_usuario, $motivo, $id_venta);
    $resultado_anula = mysqli_stmt_execute($stmt_anula);
    mysqli_stmt_close($stmt_anula);

    if (!$resultado_anula) {
        throw new Exception("Error al registrar la anulación de la venta.");
    }

    mysqli_commit($conexion);

    $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => "La venta <strong>#{$id_venta}</strong> fue anulada. Inventario restaurado y motivo registrado."];
    header("Location: listar.php");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conexion);
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => "Fallo de anulación: " . $e->getMessage()];
    header("Location: listar.php");
    exit();
}
?>
