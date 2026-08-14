<?php
// Parámetros globales del sistema: lectura centralizada desde la tabla configuracion

/**
 * Obtiene el valor de un parámetro global de configuración.
 * @param mysqli $conexion Conexión activa a la base de datos
 * @param string $clave    Nombre del parámetro
 * @param string $default  Valor por defecto si el parámetro no existe
 * @return string Valor del parámetro
 */
function obtener_configuracion($conexion, $clave, $default = '') {
    $stmt = mysqli_prepare($conexion, "SELECT valor FROM configuracion WHERE clave = ?");
    mysqli_stmt_bind_param($stmt, "s", $clave);
    mysqli_stmt_execute($stmt);
    $fila = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    mysqli_stmt_close($stmt);
    return $fila ? $fila['valor'] : $default;
}

/**
 * Umbral global de "stock bajo": productos con stock menor o igual se marcan como críticos.
 * @param mysqli $conexion Conexión activa a la base de datos
 * @return int Umbral (por defecto 5)
 */
function obtener_stock_minimo($conexion) {
    return intval(obtener_configuracion($conexion, 'stock_minimo', '5'));
}
?>
