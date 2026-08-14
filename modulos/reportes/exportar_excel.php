<?php
include("../../includes/sesion.php");
requiere_rol('admin');

// 1. Incluir el archivo de conexión a la base de datos
include("../../conexion.php");

// 2. Definir el tipo de contenido que se enviará al navegador (archivo Excel)
header("Content-Type: application/vnd.ms-excel; charset=utf-8");

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

    // 8. Imprimir los datos de cada producto separados por tabulación.
    //    Los campos de texto se neutralizan ante inyección de fórmulas de Excel (AM-011):
    //    si el valor inicia con =, +, - o @ se antepone un apóstrofe para que se trate como texto.
    $nombre = amatista_excel_texto($fila['nombre']);
    $marca  = amatista_excel_texto($fila['marca']);
    $talla  = amatista_excel_texto($fila['talla']);
    $color  = amatista_excel_texto($fila['color']);

    echo $fila['id']."\t";
    echo $nombre."\t";
    echo $marca."\t";
    echo $talla."\t";
    echo $color."\t";
    echo $fila['precio']."\t";
    echo $fila['stock']."\n";

}

/**
 * Neutraliza celdas de texto que podrían interpretarse como fórmulas en Excel.
 * También elimina tabulaciones/saltos que romperían la estructura del archivo.
 */
function amatista_excel_texto($valor) {
    $texto = (string)$valor;
    if ($texto === '') {
        return '';
    }
    if (preg_match('/^[=+\-@]/', $texto)) {
        $texto = "'" . $texto;
    }
    // Evita que un valor rompa las columnas separadas por tabulación
    $texto = str_replace(["\t", "\r", "\n"], ' ', $texto);
    return $texto;
}

?>
