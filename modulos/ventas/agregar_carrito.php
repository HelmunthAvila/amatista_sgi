<?php
session_start();
include("../../conexion.php");

$id = $_POST['producto'];
$cantidad = $_POST['cantidad'];

$producto = mysqli_query($conexion,"SELECT * FROM productos WHERE id=$id");

$p = mysqli_fetch_array($producto);

$item = [

"id"=>$p['id'],
"nombre"=>$p['nombre'],
"precio"=>$p['precio'],
"cantidad"=>$cantidad

];

$_SESSION['carrito'][] = $item;

header("Location:pos.php");