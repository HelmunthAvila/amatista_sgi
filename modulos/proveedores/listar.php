<?php
include("../../conexion.php");

$proveedores = mysqli_query($conexion,"SELECT * FROM proveedores");
?>

<h2>Proveedores</h2>

<a href="agregar.php">Registrar proveedor</a>

<table border="1">

<tr>
<th>ID</th>
<th>Nombre</th>
<th>Telefono</th>
<th>Empresa</th>
</tr>

<?php while($p = mysqli_fetch_array($proveedores)){ ?>

<tr>

<td><?php echo $p['id']; ?></td>
<td><?php echo $p['nombre']; ?></td>
<td><?php echo $p['telefono']; ?></td>
<td><?php echo $p['empresa']; ?></td>

</tr>

<?php } ?>

</table>