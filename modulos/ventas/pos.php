<?php
include("../../conexion.php");

$productos = mysqli_query($conexion,"SELECT * FROM productos");
?>

<h2>Punto de Venta</h2>

<form action="guardar_venta.php" method="POST">

Producto

<select name="producto">

<?php while($p = mysqli_fetch_array($productos)){ ?>

<option value="<?php echo $p['id']; ?>">

<?php echo $p['nombre']; ?> - $<?php echo $p['precio']; ?>

</option>

<?php } ?>

</select>

Cantidad

<input type="number" name="cantidad" required>

<br><br>

<button type="submit">Registrar Venta</button>

</form>