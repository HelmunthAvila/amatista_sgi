<?php
include("../../conexion.php");

$ventas = mysqli_query($conexion,"
SELECT * FROM ventas
WHERE DATE(fecha) = CURDATE()
");
?>

<h2>Ventas del Día</h2>

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