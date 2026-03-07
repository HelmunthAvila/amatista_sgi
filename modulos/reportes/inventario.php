<?php
include("../../conexion.php");

$productos = mysqli_query($conexion,"SELECT * FROM productos");
?>

<h2>Inventario General</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Producto</th>
<th>Marca</th>
<th>Talla</th>
<th>Precio</th>
<th>Stock</th>
</tr>

<?php while($p = mysqli_fetch_array($productos)){ ?>

<tr>

<td><?php echo $p['id']; ?></td>
<td><?php echo $p['nombre']; ?></td>
<td><?php echo $p['marca']; ?></td>
<td><?php echo $p['talla']; ?></td>
<td><?php echo $p['precio']; ?></td>
<td><?php echo $p['stock']; ?></td>

</tr>

<?php } ?>

</table>