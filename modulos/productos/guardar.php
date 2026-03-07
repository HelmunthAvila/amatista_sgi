<?php
include("../../conexion.php");

$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$talla = $_POST['talla'];
$color = $_POST['color'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$sql = "INSERT INTO productos(nombre,marca,talla,color,precio,stock)
VALUES('$nombre','$marca','$talla','$color','$precio','$stock')";

mysqli_query($conexion,$sql);

header("Location:listar.php");
?>