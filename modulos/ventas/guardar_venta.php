<?php
include("../../conexion.php");

$id_producto = $_POST['producto'];
$cantidad = $_POST['cantidad'];

$producto = mysqli_query($conexion,"SELECT * FROM productos WHERE id=$id_producto");

$p = mysqli_fetch_array($producto);

$precio = $p['precio'];

$total = $precio * $cantidad;

mysqli_query($conexion,"INSERT INTO ventas(fecha,total) VALUES(NOW(),'$total')");

$id_venta = mysqli_insert_id($conexion);

mysqli_query($conexion,"INSERT INTO detalle_venta(id_venta,id_producto,cantidad,precio)
VALUES('$id_venta','$id_producto','$cantidad','$precio')");

mysqli_query($conexion,"UPDATE productos 
SET stock = stock - $cantidad
WHERE id=$id_producto");

header("Location:ticket.php?id=$id_venta");

?>