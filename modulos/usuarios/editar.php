<?php

// 1. Incluir conexión a la base de datos
include("../../conexion.php");

// 2. Incluir encabezado del sistema (menú, estilos, navbar)
include("../../includes/header.php");

/*
----------------------------------------------------
OBTENER DATOS DEL USUARIO A EDITAR
----------------------------------------------------
Se recibe el ID del usuario por la URL
ejemplo: editar.php?id=3
*/
$id = $_GET['id'];

/* 
Consultar la base de datos para obtener
los datos actuales del usuario
*/
$resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = '$id'");

// Guardar los datos del usuario en un arreglo
$u = mysqli_fetch_array($resultado);

?>

<div class="container-fluid">

    <!-- TITULO -->
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">Editar Usuario</h2>
        <p class="text-muted small">
        Modifica los permisos o datos de acceso.
        </p>
    </div>

    <div class="row">

        <div class="col-md-6">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- 
                FORMULARIO DE ACTUALIZACIÓN
                Los datos se envían a actualizar.php
                -->
                <form action="actualizar.php" method="POST">

                    <!-- ID oculto del usuario -->
                    <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                    
                    <!-- NOMBRE COMPLETO -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">
                        Nombre Completo
                        </label>

                        <input 
                        type="text"
                        name="nombre"
                        class="form-control rounded-3"
                        value="<?php echo $u['nombre']; ?>"
                        required>

                    </div>
                    
                    <!-- USUARIO -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">
                        Usuario
                        </label>

                        <input 
                        type="text"
                        name="usuario"
                        class="form-control rounded-3"
                        value="<?php echo $u['usuario']; ?>"
                        required>

                    </div>

                    <!-- CONTRASEÑA -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">
                        Nueva Contraseña
                        </label>

                        <!-- 
                        Si se deja vacío,
                        el sistema no cambia la contraseña
                        -->
                        <input 
                        type="password"
                        name="password"
                        class="form-control rounded-3"
                        placeholder="Dejar en blanco para no cambiar">

                    </div>

                    <!-- ROL DEL USUARIO -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">
                        Rol
                        </label>

                        <select name="rol" class="form-select rounded-3">

                            <option value="cajero" 
                            <?php if($u['rol'] == 'cajero') echo 'selected'; ?>>
                            Cajero
                            </option>

                            <option value="admin" 
                            <?php if($u['rol'] == 'admin') echo 'selected'; ?>>
                            Administrador
                            </option>

                        </select>

                    </div>

                    <!-- ESTADO DEL USUARIO -->
                    <div class="mb-4">

                        <label class="form-label small fw-bold text-muted">
                        Estado
                        </label>

                        <select name="estado" class="form-select rounded-3">

                            <option value="1" 
                            <?php if($u['estado'] == 1) echo 'selected'; ?>>
                            Activo
                            </option>

                            <option value="0" 
                            <?php if($u['estado'] == 0) echo 'selected'; ?>>
                            Inactivo
                            </option>

                        </select>

                    </div>

                    <!-- BOTONES -->
                    <div class="d-flex gap-2">

                        <!-- Actualizar datos -->
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                        Actualizar Datos
                        </button>

                        <!-- Regresar al listado -->
                        <a href="listar.php" class="btn btn-light rounded-pill px-4">
                        Volver
                        </a>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

// 3. Incluir pie de página del sistema
include("../../includes/footer.php");

?>