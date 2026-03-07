<?php
include("../../conexion.php");

$id = $_GET['id'];

$venta = mysqli_query($conexion,"SELECT * FROM ventas WHERE id=$id");

$v = mysqli_fetch_array($venta);

$detalle = mysqli_query($conexion,"
SELECT p.nombre,d.cantidad,d.precio
FROM detalle_venta d
JOIN productos p ON p.id=d.id_producto
WHERE d.id_venta=$id
");
?>

<h2>Ticket de Venta</h2>

Fecha: <?php echo $v['fecha']; ?>

<table border="1">

<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio</th>
</tr>

<?php while($d = mysqli_fetch_array($detalle)){ ?>

<tr>
<td><?php echo $d['nombre']; ?></td>
<td><?php echo $d['cantidad']; ?></td>
<td><?php echo $d['precio']; ?></td>
</tr>

<?php } ?>

</table>

<h3>Total: $<?php echo $v['total']; ?></h3>