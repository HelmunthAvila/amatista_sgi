<?php
include("../../conexion.php");

$clientes = mysqli_query($conexion,"SELECT * FROM clientes");
?>

<h2>Clientes</h2>

<a href="agregar.php">Registrar cliente</a>

<table border="1">

<tr>
<th>ID</th>
<th>Nombre</th>
<th>Telefono</th>
<th>Email</th>
</tr>

<?php while($c = mysqli_fetch_array($clientes)){ ?>

<tr>

<td><?php echo $c['id']; ?></td>
<td><?php echo $c['nombre']; ?></td>
<td><?php echo $c['telefono']; ?></td>
<td><?php echo $c['email']; ?></td>

</tr>

<?php } ?>

</table>