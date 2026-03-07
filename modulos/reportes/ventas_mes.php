<?php
include("../../conexion.php");

$ventas = mysqli_query($conexion,"
SELECT * FROM ventas
WHERE MONTH(fecha)=MONTH(CURDATE())
");
?>

<h2>Ventas del Mes</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Fecha</th>
<th>Total</th>
</tr>

<?php while($v = mysqli_fetch_array($ventas)){ ?>

<tr>

<td><?php echo $v['id']; ?></td>
<td><?php echo $v['fecha']; ?></td>
<td><?php echo $v['total']; ?></td>

</tr>

<?php } ?>

</table>