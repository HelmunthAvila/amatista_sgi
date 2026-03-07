<?php
include("../../conexion.php");

$productos = mysqli_query($conexion,"SELECT * FROM productos");
?>

<h2>Inventario de Zapatos</h2>

<a href="agregar.php">Agregar producto</a>

<table border="1">

<tr>
<th>ID</th>
<th>Nombre</th>
<th>Marca</th>
<th>Talla</th>
<th>Color</th>
<th>Precio</th>
<th>Stock</th>
<th>Acciones</th>
</tr>

<?php while($p = mysqli_fetch_array($productos)){ ?>

<tr>

<td><?php echo $p['id']; ?></td>
<td><?php echo $p['nombre']; ?></td>
<td><?php echo $p['marca']; ?></td>
<td><?php echo $p['talla']; ?></td>
<td><?php echo $p['color']; ?></td>
<td><?php echo $p['precio']; ?></td>
<td><?php echo $p['stock']; ?></td>

<td>

<a href="editar.php?id=<?php echo $p['id']; ?>">Editar</a>
|
<a href="eliminar.php?id=<?php echo $p['id']; ?>">Eliminar</a>

</td>

</tr>

<?php } ?>

</table>