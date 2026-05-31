<?php
session_start(); 
include("../../conexion.php");

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'No se especificó un ID de venta válido para anular.'];
    header("Location: listar.php");
    exit();
}

$id_venta = intval($_GET['id']);
mysqli_begin_transaction($conexion);

try {
    $query_detalle = mysqli_query($conexion, "SELECT id_producto, cantidad FROM detalle_venta WHERE id_venta = $id_venta");
    
    if (!$query_detalle) {
        throw new Exception("Error al consultar el desglose de productos.");
    }

    while ($d = mysqli_fetch_assoc($query_detalle)) {
        $id_producto = $d['id_producto'];
        $cantidad = $d['cantidad'];
        
        $update_stock = "UPDATE productos SET stock = stock + $cantidad WHERE id = $id_producto";
        $resultado_stock = mysqli_query($conexion, $update_stock);
        
        if (!$resultado_stock) {
            throw new Exception("Error al restaurar las existencias en inventarios.");
        }
    }

    $delete_detalle = mysqli_query($conexion, "DELETE FROM detalle_venta WHERE id_venta = $id_venta");
    if (!$delete_detalle) {
        throw new Exception("Error al purgar los registros hijos (detalle).");
    }

    $delete_venta = mysqli_query($conexion, "DELETE FROM ventas WHERE id = $id_venta");
    if (!$delete_venta) {
        throw new Exception("Error al eliminar el registro maestro de la venta.");
    }

    mysqli_commit($conexion);
    
    $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => "La venta <strong>#{$id_venta}</strong> fue anulada con éxito. Inventario restaurado."];
    header("Location: listar.php");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conexion);
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => "Fallo de anulación: " . $e->getMessage()];
    header("Location: listar.php");
    exit();
}
?>