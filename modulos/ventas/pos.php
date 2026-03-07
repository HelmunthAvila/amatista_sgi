<?php
session_start();
include("../../conexion.php");

$productos = mysqli_query($conexion,"SELECT * FROM productos");

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}
?>

<h2>Punto de Venta - AMATISTA SGI</h2>

<form action="agregar_carrito.php" method="POST">

Producto

<select name="producto">

<?php while($p = mysqli_fetch_array($productos)){ ?>

<option value="<?php echo $p['id']; ?>">

<?php echo $p['nombre']." - $".$p['precio']; ?>

</option>

<?php } ?>

</select>

Cantidad

<input type="number" name="cantidad" required>

<button type="submit">Agregar</button>

</form>

<hr>

<h3>Carrito</h3>

<table border="1">

<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Precio</th>
<th>Subtotal</th>
<th>Acción</th>
</tr>

<?php

$total = 0;

foreach($_SESSION['carrito'] as $index => $item){

$subtotal = $item['precio'] * $item['cantidad'];

$total += $subtotal;

?>

<tr>

<td><?php echo $item['nombre']; ?></td>
<td><?php echo $item['cantidad']; ?></td>
<td><?php echo $item['precio']; ?></td>
<td><?php echo $subtotal; ?></td>

<td>

<a href="eliminar_carrito.php?id=<?php echo $index; ?>">Eliminar</a>

</td>

</tr>

<?php } ?>

</table>

<h3>Total: $ <?php echo $total; ?></h3>

<form action="guardar_venta.php" method="POST">

<button type="submit">Finalizar Venta</button>

</form>