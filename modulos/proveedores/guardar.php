<?php
include("../../conexion.php");

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$empresa = $_POST['empresa'];

$sql = "INSERT INTO proveedores(nombre,telefono,empresa)
VALUES('$nombre','$telefono','$empresa')";

mysqli_query($conexion,$sql);

header("Location:listar.php");

?>