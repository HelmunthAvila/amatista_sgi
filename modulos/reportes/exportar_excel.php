<?php
include("../../conexion.php");

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=inventario.xls");

echo "ID\tProducto\tMarca\tTalla\tColor\tPrecio\tStock\n";

$query = "SELECT * FROM productos";
$resultado = mysqli_query($conexion,$query);

while($fila = mysqli_fetch_assoc($resultado)){

echo $fila['id']."\t";
echo $fila['nombre']."\t";
echo $fila['marca']."\t";
echo $fila['talla']."\t";
echo $fila['color']."\t";
echo $fila['precio']."\t";
echo $fila['stock']."\n";

}
?>