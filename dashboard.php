<?php
session_start();

if(!isset($_SESSION['usuario'])){
header("Location: login.php");
}
?>

<h1>Sistema AMATISTA SGI</h1>

Bienvenido: <?php echo $_SESSION['usuario']; ?>

<br><br>

<a href="logout.php">Cerrar sesión</a>