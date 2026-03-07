<?php
include("conexion.php");

$ventas_dia = mysqli_query($conexion,"
SELECT SUM(total) as total 
FROM ventas 
WHERE DATE(fecha)=CURDATE()
");

$vd = mysqli_fetch_array($ventas_dia);

$productos = mysqli_query($conexion,"
SELECT COUNT(*) as total FROM productos
");

$tp = mysqli_fetch_array($productos);
?>

<h1>Dashboard AMATISTA SGI</h1>

<div>

<h3>Ventas del día</h3>
$ <?php echo $vd['total']; ?>

</div>

<div>

<h3>Total productos</h3>
<?php echo $tp['total']; ?>

</div>

<?php

$stock = mysqli_query($conexion,"
SELECT * FROM productos
WHERE stock <= 5
");

?>

<h3>Productos con stock bajo</h3>

<ul>

<?php while($s=mysqli_fetch_array($stock)){ ?>

<li>

<?php echo $s['nombre']; ?> 
(stock: <?php echo $s['stock']; ?>)

</li>

<?php } ?>

</ul>