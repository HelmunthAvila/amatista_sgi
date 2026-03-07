<?php
include("../../conexion.php");

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$marca = $_POST['marca'];
$talla = $_POST['talla'];
$color = $_POST['color'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$sql = "UPDATE productos SET
nombre='$nombre',
marca='$marca',
talla='$talla',
color='$color',
precio='$precio',
stock='$stock'
WHERE id=$id";

mysqli_query($conexion,$sql);

header("Location:listar.php");

?>