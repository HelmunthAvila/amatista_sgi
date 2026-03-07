<?php

$host="localhost";
$user="root";
$pass="";
$db="amatista_sgi";

$conexion = mysqli_connect($host,$user,$pass,$db);

if(!$conexion){
    die("Error de conexión");
}

?>