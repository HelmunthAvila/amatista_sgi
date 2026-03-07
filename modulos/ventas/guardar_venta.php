<?php
session_start();
include("../../conexion.php");

$total = 0;

foreach($_SESSION['carrito'] as $item){

$total += $item['precio'] * $item['cantidad'];

}

mysqli_query($conexion,"
INSERT INTO ventas(fecha,total)
VALUES(NOW(),'$total')
");

$id_venta = mysqli_insert_id($conexion);

foreach($_SESSION['carrito'] as $item){

$id_producto = $item['id'];
$cantidad = $item['cantidad'];
$precio = $item['precio'];

mysqli_query($conexion,"
INSERT INTO detalle_venta
(id_venta,id_producto,cantidad,precio)
VALUES
('$id_venta','$id_producto','$cantidad','$precio')
");

mysqli_query($conexion,"
UPDATE productos
SET stock = stock - $cantidad
WHERE id=$id_producto
");

}

unset($_SESSION['carrito']);

header("Location:pos.php");