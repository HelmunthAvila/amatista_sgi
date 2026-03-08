<?php
// 1. Inclusión de archivos base y recuperación de ID
include("../../conexion.php");
include("../../includes/header.php");

$id = $_GET['id'];
$query = mysqli_query($conexion, "SELECT * FROM clientes WHERE id = $id");
$c = mysqli_fetch_assoc($query);
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h2 class="fw-bold text-dark">Editar Información del Cliente</h2>
        <p class="text-muted small">Modifique los campos necesarios para actualizar al cliente: <strong><?= $c['nombre'] ?></strong></p>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form action="actualizar.php" method="POST">
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-person text-primary"></i></span>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $c['nombre'] ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Teléfono / Celular</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-telephone text-primary"></i></span>
                            <input type="text" name="telefono" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $c['telefono'] ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-envelope text-primary"></i></span>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $c['email'] ?>">
                        </div>
                    </div>

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
// 2. Cierre del layout
include("../../includes/footer.php"); 
?>