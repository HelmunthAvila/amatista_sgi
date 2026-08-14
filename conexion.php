<?php

// Configura y establece la conexión a la base de datos MySQL del sistema Amatista SGI
$host="localhost";
$user="root";
$pass="";
$db="amatista_sgi";

$conexion = mysqli_connect($host,$user,$pass,$db);

// Verifica si la conexión falló y detiene la ejecución mostrando un error
if(!$conexion){
    die("Error de conexión");
}

// Charset UTF-8 explícito para evitar problemas con tildes y caracteres especiales (AM-012)
mysqli_set_charset($conexion, "utf8mb4");

?>