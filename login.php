<?php
session_start();
include("conexion.php");

if(isset($_POST['usuario'])){

$usuario = $_POST['usuario'];
$password = md5($_POST['password']);

$sql = "SELECT * FROM usuarios 
WHERE usuario='$usuario' 
AND password='$password'";

$resultado = mysqli_query($conexion,$sql);

if(mysqli_num_rows($resultado)>0){

$_SESSION['usuario']=$usuario;

header("Location: dashboard.php");

}else{

echo "Usuario o contraseña incorrectos";

}

}
?>

<h2>AMATISTA SGI</h2>

<form method="POST">

Usuario
<input type="text" name="usuario">

Password
<input type="password" name="password">

<button type="submit">Ingresar</button>

</form>