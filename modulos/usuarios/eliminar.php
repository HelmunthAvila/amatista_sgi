<?php
include("../../includes/sesion.php");
// 1. Incluimos la conexión a la base de datos usando la ruta correcta
include("../../conexion.php");

// 2. Validamos que el ID haya sido enviado por la URL y que no esté vacío
if (isset($_GET['id']) && !empty($_GET['id'])) {
    
    // Escapamos el ID por seguridad contra inyecciones SQL simples
    $id_usuario = mysqli_real_escape_string($conexion, $_GET['id']);
    
    // 3. Ejecutamos la sentencia de eliminación
    $query = "DELETE FROM usuarios WHERE id = '$id_usuario'";
    $resultado = mysqli_query($conexion, $query);
    
    // Opcional: Podrías enviar un mensaje de éxito o error por una variable de sesión.
    if ($resultado) {
        // Si todo sale bien, redirige a la lista
        header("Location: listar.php?status=deleted");
        exit();
    } else {
        // Si hay error en la query de la base de datos
        echo "Error al intentar eliminar el usuario: " . mysqli_error($conexion);
    }
    
} else {
    // Si intentan entrar a la mala sin pasar un ID en la URL, los mandamos de regreso
    header("Location: listar.php");
    exit();
}
?>