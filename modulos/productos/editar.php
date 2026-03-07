<?php
include("../../conexion.php");

$id = $_GET['id'];

$producto = mysqli_query($conexion,"SELECT * FROM productos WHERE id=$id");

$p = mysqli_fetch_array($producto);
?>

<h2>Editar Producto</h2>

<form action="actualizar.php" method="POST">

<input type="hidden" name="id" value="<?php echo $p['id']; ?>">

Nombre
<input type="text" name="nombre" value="<?php echo $p['nombre']; ?>">

Marca
<input type="text" name="marca" value="<?php echo $p['marca']; ?>">

Talla
<input type="text" name="talla" value="<?php echo $p['talla']; ?>">

Color
<input type="text" name="color" value="<?php echo $p['color']; ?>">

Precio
<input type="number" step="0.01" name="precio" value="<?php echo $p['precio']; ?>">

Stock
<input type="number" name="stock" value="<?php echo $p['stock']; ?>">

<br><br>

<button type="submit">Actualizar</button>

</form>