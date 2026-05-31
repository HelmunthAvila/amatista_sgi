<?php
session_start();
include("../../conexion.php");

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
    $query_venta = "INSERT INTO ventas (id_cliente, fecha, total) VALUES ('$id_cliente','$fecha','$total_venta')";
    $resultado_venta = mysqli_query($conexion, $query_venta);

    if(!$resultado_venta){
        throw new Exception("Error al guardar la cabecera de la venta.");
    }

    $id_venta = mysqli_insert_id($conexion);

    foreach($_SESSION['carrito'] as $item){
        $id_producto = intval($item['id']);
        $cantidad = intval($item['cantidad']);
        $precio = floatval($item['precio']);

        $query_detalle = "INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario) VALUES ('$id_venta','$id_producto','$cantidad','$precio')";
        if(!mysqli_query($conexion, $query_detalle)){
            throw new Exception("Error al registrar el detalle del producto ID: $id_producto");
        }

        $update_stock = "UPDATE productos SET stock = stock - $cantidad WHERE id = $id_producto";
        if(!mysqli_query($conexion, $update_stock)){
            throw new Exception("Error al descontar el inventario del producto ID: $id_producto");
        }
    }

    mysqli_commit($conexion);
    unset($_SESSION['carrito']);

    $_SESSION['alerta'] = ['tipo' => 'success', 'mensaje' => "¡Venta <strong>#{$id_venta}</strong> registrada exitosamente! Listo para despacho comercial."];
    header("Location: listar.php");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conexion);
    $_SESSION['alerta'] = ['tipo' => 'danger', 'mensaje' => "Error al procesar transacciones: " . $e->getMessage()];
    header("Location: pos.php");
    exit();
}
?>