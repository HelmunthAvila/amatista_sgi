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

?>