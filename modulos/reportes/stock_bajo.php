<?php
include("../../conexion.php");

$productos = mysqli_query($conexion,"
SELECT * FROM productos
WHERE stock <= 5
");
?>

<h2>Productos con Stock Bajo</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Producto</th>
<th>Stock</th>
</tr>

<?php while($p = mysqli_fetch_array($productos)){ ?>

<tr>

<td><?php echo $p['id']; ?></td>
<td><?php echo $p['nombre']; ?></td>
<td><?php echo $p['stock']; ?></td>

</tr>

<?php } ?>

</table>