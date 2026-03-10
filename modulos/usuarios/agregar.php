<?php

// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Incluir encabezado del sistema (menú, estilos, navbar)
include("../../includes/header.php");

?>

<div class="container-fluid">

    <!-- TITULO DEL FORMULARIO -->
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">Nuevo Usuario</h2>
        <p class="text-muted small">
        Define las credenciales de acceso al sistema.
        </p>
    </div>

    <div class="row">

        <!-- FORMULARIO DE REGISTRO DE USUARIO -->
        <div class="col-md-6">

            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- Formulario que envía los datos al archivo guardar.php -->
                <form action="guardar.php" method="POST">

                    <!-- CAMPO: NOMBRE COMPLETO -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">
                        Nombre Completo
                        </label>

                        <input 
                        type="text"
                        name="nombre"
                        class="form-control rounded-3"
                        placeholder="Ej. Juan Pérez"
                        required>

                    </div>
                    
                    <!-- CAMPO: USUARIO (LOGIN) -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">
                        Nombre de Usuario (Login)
                        </label>

                        <div class="input-group">

                            <!-- Icono de usuario -->
                            <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-at"></i>
                            </span>

                            <input 
                            type="text"
                            name="usuario"
                            class="form-control rounded-3 border-start-0"
                            placeholder="usuario123"
                            required>

                        </div>

                    </div>

                    <!-- CAMPO: CONTRASEÑA -->
                    <div class="mb-3">

                        <label class="form-label small fw-bold text-muted">
                        Contraseña
                        </label>

                        <input 
                        type="password"
                        name="password"
                        class="form-control rounded-3"
                        required>

                    </div>

                    <!-- CAMPO: ROL DEL USUARIO -->
                    <div class="mb-4">

                        <label class="form-label small fw-bold text-muted">
                        Rol de Usuario
                        </label>

                        <select name="rol" class="form-select rounded-3">

                            <!-- Usuario de ventas -->
                            <option value="cajero">
                            Cajero (Ventas)
                            </option>

                            <!-- Usuario administrador -->
                            <option value="admin">
                            Administrador (Acceso total)
                            </option>

                        </select>

                    </div>

                    <!-- BOTONES DEL FORMULARIO -->
                    <div class="d-flex gap-2">

                        <!-- Guardar usuario -->
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Guardar Usuario
                        </button>

                        <!-- Cancelar y regresar al listado -->
                        <a href="listar.php" class="btn btn-light rounded-pill px-4">
                        Cancelar
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