<?php
include("../../conexion.php");

$nombre = $_POST['nombre'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];

$sql = "INSERT INTO clientes(nombre,telefono,email)
VALUES('$nombre','$telefono','$email')";

mysqli_query($conexion,$sql);

header("Location:listar.php");

?>