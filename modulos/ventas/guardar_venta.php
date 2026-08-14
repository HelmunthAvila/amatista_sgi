<?php
include("../../includes/sesion.php");

include("../../conexion.php");
// Verificación CSRF (AM-008)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !csrf_verificar()) {
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'La sesión expiró. Recarga la página e intenta nuevamente.'];
    header("Location: pos.php");
    exit();
}


if(!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Operación cancelada. El carrito de compras se encuentra vacío.'];
    header("Location: pos.php");
    exit();
}

if(!isset($_POST['id_cliente']) || empty($_POST['id_cliente'])){
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => 'Debe asignar obligatoriamente un cliente a la venta.'];
    header("Location: pos.php");
    exit();
}

$id_cliente = intval($_POST['id_cliente']);
date_default_timezone_set('America/Bogota'); // Ajusta según tu zona horaria
$fecha = date("Y-m-d H:i:s");
$total_venta = 0;

foreach($_SESSION['carrito'] as $item){
    $subtotal = $item['precio'] * $item['cantidad'];
    $total_venta += $subtotal;
}

// Iniciar transacción por seguridad e integridad de datos
mysqli_begin_transaction($conexion);

try {
    // Consultas preparadas (AM-005): cabecera, detalle y descuento de stock
    $stmt_venta = mysqli_prepare($conexion, "INSERT INTO ventas (id_cliente, fecha, total) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt_venta, "iss", $id_cliente, $fecha, $total_venta);
    $resultado_venta = mysqli_stmt_execute($stmt_venta);
    mysqli_stmt_close($stmt_venta);

    if(!$resultado_venta){
        throw new Exception("Error al guardar la cabecera de la venta.");
    }

    $id_venta = mysqli_insert_id($conexion);

    foreach($_SESSION['carrito'] as $item){
        $id_producto = intval($item['id']);
        $cantidad = intval($item['cantidad']);
        $precio = floatval($item['precio']);

        // Revalidación de stock al momento de la venta (AM-013): evita stock negativo
        // si el inventario cambió después de agregar el producto al carrito.
        $stmt_check = mysqli_prepare($conexion, "SELECT stock FROM productos WHERE id = ?");
        mysqli_stmt_bind_param($stmt_check, "i", $id_producto);
        mysqli_stmt_execute($stmt_check);
        $fila_check = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_check));
        mysqli_stmt_close($stmt_check);

        if (!$fila_check || intval($fila_check['stock']) < $cantidad) {
            throw new Exception("Stock insuficiente para el producto ID: $id_producto");
        }

        $stmt_detalle = mysqli_prepare($conexion, "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt_detalle, "iiid", $id_venta, $id_producto, $cantidad, $precio);
        $ok_detalle = mysqli_stmt_execute($stmt_detalle);
        mysqli_stmt_close($stmt_detalle);
        if(!$ok_detalle){
            throw new Exception("Error al registrar el detalle del producto ID: $id_producto");
        }

        $stmt_stock = mysqli_prepare($conexion, "UPDATE productos SET stock = stock - ? WHERE id = ?");
        mysqli_stmt_bind_param($stmt_stock, "ii", $cantidad, $id_producto);
        $ok_stock = mysqli_stmt_execute($stmt_stock);
        mysqli_stmt_close($stmt_stock);
        if(!$ok_stock){
            throw new Exception("Error al descontar el inventario del producto ID: $id_producto");
        }
    }

    mysqli_commit($conexion);
    unset($_SESSION['carrito']);

    // REDIRECCIÓN DIRECTA A POS PARA LEVANTAR EL SCRIPT DE IMPRESIÓN
    $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => "¡Venta <strong>#{$id_venta}</strong> registrada exitosamente! El ticket de impresión se abrirá automáticamente."];
    $_SESSION['imprimir_ticket_id'] = $id_venta; // Variable de enganche para pos.php
    
    header("Location: pos.php");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conexion);
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => "Error al procesar transacciones: " . $e->getMessage()];
    header("Location: pos.php");
    exit();
}
?>