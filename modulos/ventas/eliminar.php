<?php
// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Verificar que el ID de la venta esté presente
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: listar.php");
    exit();
}

$id_venta = intval($_GET['id']);

// 3. INICIAR TRANSACCIÓN SQL (Para asegurar que se devuelva el stock antes de borrar)
mysqli_begin_transaction($conexion);

try {
    // A. Consultar los productos y cantidades que se vendieron en esta factura
    $query_detalle = mysqli_query($conexion, "SELECT id_producto, cantidad FROM detalle_venta WHERE id_venta = $id_venta");
    
    if (!$query_detalle) {
        throw new Exception("Error al consultar el detalle de la venta.");
    }

    // B. Devolver el stock a cada producto (Sumar lo que se había restado)
    while ($d = mysqli_fetch_assoc($query_detalle)) {
        $id_producto = $d['id_producto'];
        $cantidad = $d['cantidad'];
        
        $update_stock = "UPDATE productos SET stock = stock + $cantidad WHERE id = $id_producto";
        $resultado_stock = mysqli_query($conexion, $update_stock);
        
        if (!$resultado_stock) {
            throw new Exception("Error al actualizar el inventario.");
        }
    }

    // C. Eliminar los registros del detalle de la venta (Por la integridad de llaves foráneas)
    $delete_detalle = mysqli_query($conexion, "DELETE FROM detalle_venta WHERE id_venta = $id_venta");
    if (!$delete_detalle) {
        throw new Exception("Error al eliminar los detalles de la venta.");
    }

    // D. Eliminar el encabezado de la venta
    $delete_venta = mysqli_query($conexion, "DELETE FROM ventas WHERE id = $id_venta");
    if (!$delete_venta) {
        throw new Exception("Error al eliminar la venta principal.");
    }

    // Si todo salió bien, guardamos los cambios definitivamente
    mysqli_commit($conexion);
    
    // Redirigir al historial indicando éxito
    header("Location: listar.php?status=canceled");
    exit();

} catch (Exception $e) {
    // Si algo falla, cancelamos todas las operaciones para no corromper la base de datos
    mysqli_rollback($conexion);
    die("No se pudo anular la venta. Detalle: " . $e->getMessage());
}
?>