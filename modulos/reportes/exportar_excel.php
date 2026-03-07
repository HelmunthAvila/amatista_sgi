<?php

include("../../conexion.php");

header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=ventas.xls");

$ventas = mysqli_query($conexion,"SELECT * FROM ventas");

echo "ID\tFecha\tTotal\n";

while($v=mysqli_fetch_array($ventas)){

echo $v['id']."\t".$v['fecha']."\t".$v['total']."\n";

}

?>