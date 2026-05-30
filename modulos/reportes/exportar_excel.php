<?php

// 1. Incluir el archivo de conexión a la base de datos
include("../../conexion.php");

// 2. Definir el tipo de contenido que se enviará al navegador (archivo Excel)
header("Content-Type: application/xls");

// 3. Definir el nombre del archivo que se descargará
header("Content-Disposition: attachment; filename=inventario.xls");

// 4. Imprimir los encabezados de las columnas separados por tabulación
echo "ID\tProducto\tMarca\tTalla\tColor\tPrecio\tStock\n";

// 5. Consulta para obtener todos los productos del inventario
$query = "SELECT * FROM productos";

// 6. Ejecutar la consulta en la base de datos
$resultado = mysqli_query($conexion,$query);

// 7. Recorrer cada registro obtenido de la base de datos
while($fila = mysqli_fetch_assoc($resultado)){

    // 8. Imprimir los datos de cada producto separados por tabulación
    echo $fila['id']."\t";
    echo $fila['nombre']."\t";
    echo $fila['marca']."\t";
    echo $fila['talla']."\t";
    echo $fila['color']."\t";
    echo $fila['precio']."\t";
    echo $fila['stock']."\n";

}

?>