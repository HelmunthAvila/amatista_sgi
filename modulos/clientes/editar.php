<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Obtiene el ID del cliente enviado desde el listado
$id = $_GET['id'];

// Consulta la información del cliente seleccionado
$query = mysqli_query($conexion, "SELECT * FROM clientes WHERE id = $id");

// Convierte el resultado en un arreglo asociativo
$c = mysqli_fetch_assoc($query);
?>

<!-- Contenedor principal del módulo de edición de clientes -->
<div class="container-fluid">

    <!-- Encabezado con botón para regresar al listado -->
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

        <!-- Título de la página -->
        <h2 class="fw-bold text-dark">Editar Información del Cliente</h2>

        <!-- Mensaje que indica qué cliente se está editando -->
        <p class="text-muted small">
            Modifique los campos necesarios para actualizar al cliente: 
            <strong><?= $c['nombre'] ?></strong>
        </p>
    </div>

    <div class="row">

        <!-- Columna donde se encuentra el formulario de edición -->
        <div class="col-xl-5 col-lg-7">

            <!-- Tarjeta que contiene el formulario -->
            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- Formulario que envía los datos al archivo actualizar.php -->
                <form action="actualizar.php" method="POST">

                    <!-- Campo oculto para enviar el ID del cliente -->
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    
                    <!-- Campo para editar el nombre del cliente -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-person text-primary"></i>
                            </span>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $c['nombre'] ?>" required>
                        </div>
                    </div>

                    <!-- Campo para editar el teléfono del cliente -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Teléfono / Celular</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-telephone text-primary"></i>
                            </span>
                            <input type="text" name="telefono" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $c['telefono'] ?>">
                        </div>
                    </div>

                    <!-- Campo para editar el correo electrónico -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $c['email'] ?>">
                        </div>
                    </div>

                    <!-- Botón para guardar los cambios en la base de datos -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3">
                            <i class="bi bi-save-fill me-2"></i>Actualizar Cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// Incluye el pie de página del sistema
include("../../includes/footer.php"); 
?>