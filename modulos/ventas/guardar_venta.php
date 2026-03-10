<?php
session_start();
include("../../conexion.php");

// 1. Verificar que el carrito tenga productos
if(!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])){
    header("Location: pos.php?error=carrito_vacio");
    exit();
}

// 2. Capturar cliente
if(!isset($_POST['id_cliente'])){
    header("Location: pos.php?error=cliente_requerido");
    exit();
}

$id_cliente = intval($_POST['id_cliente']);
$fecha = date("Y-m-d H:i:s");
$total_venta = 0;

// 3. Calcular total de la venta
foreach($_SESSION['carrito'] as $item){
    $subtotal = $item['precio'] * $item['cantidad'];
    $total_venta += $subtotal;
}

// 4. Insertar venta
$query_venta = "INSERT INTO ventas (id_cliente, fecha, total) 
                VALUES ('$id_cliente','$fecha','$total_venta')";

$resultado_venta = mysqli_query($conexion, $query_venta);

if(!$resultado_venta){
    die("Error al registrar venta: " . mysqli_error($conexion));
}

// Obtener ID de la venta creada
$id_venta = mysqli_insert_id($conexion);

// 5. Insertar detalle de productos
foreach($_SESSION['carrito'] as $item){

    $id_producto = $item['id'];
    $cantidad = $item['cantidad'];
    $precio = $item['precio'];

    $query_detalle = "INSERT INTO detalle_venta 
                      (id_venta, id_producto, cantidad, precio_unitario)
                      VALUES 
                      ('$id_venta','$id_producto','$cantidad','$precio')";

    $resultado_detalle = mysqli_query($conexion, $query_detalle);

    if(!$resultado_detalle){
        die("Error al registrar detalle: " . mysqli_error($conexion));
    }

    // 6. Descontar stock
    $update_stock = "UPDATE productos 
                     SET stock = stock - $cantidad 
                     WHERE id = $id_producto";

    mysqli_query($conexion, $update_stock);
}

// 7. Vaciar carrito
unset($_SESSION['carrito']);

// 8. Redirigir al listado
header("Location: listar.php?res=exito&id=".$id_venta);
exit();
?>