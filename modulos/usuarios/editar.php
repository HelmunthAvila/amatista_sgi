<?php
include("../../conexion.php");
include("../../includes/header.php");

$id = $_GET['id'];
$resultado = mysqli_query($conexion, "SELECT * FROM usuarios WHERE id = '$id'");
$u = mysqli_fetch_array($resultado);
?>

<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">Editar Usuario</h2>
        <p class="text-muted small">Modifica los permisos o datos de acceso.</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form action="actualizar.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control rounded-3" value="<?php echo $u['nombre']; ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Usuario</label>
                        <input type="text" name="usuario" class="form-control rounded-3" value="<?php echo $u['usuario']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control rounded-3" placeholder="Dejar en blanco para no cambiar">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Rol</label>
                        <select name="rol" class="form-select rounded-3">
                            <option value="cajero" <?php if($u['rol'] == 'cajero') echo 'selected'; ?>>Cajero</option>
                            <option value="admin" <?php if($u['rol'] == 'admin') echo 'selected'; ?>>Administrador</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Estado</label>
                        <select name="estado" class="form-select rounded-3">
                            <option value="1" <?php if($u['estado'] == 1) echo 'selected'; ?>>Activo</option>
                            <option value="0" <?php if($u['estado'] == 0) echo 'selected'; ?>>Inactivo</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Actualizar Datos</button>
                        <a href="listar.php" class="btn btn-light rounded-pill px-4">Volver</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>