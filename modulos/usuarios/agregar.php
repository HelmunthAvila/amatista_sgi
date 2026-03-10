<?php
include("../../conexion.php");
include("../../includes/header.php");
?>

<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">Nuevo Usuario</h2>
        <p class="text-muted small">Define las credenciales de acceso al sistema.</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form action="guardar.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nombre Completo</label>
                        <input type="text" name="nombre" class="form-control rounded-3" placeholder="Ej. Juan Pérez" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nombre de Usuario (Login)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-at"></i></span>
                            <input type="text" name="usuario" class="form-control rounded-3 border-start-0" placeholder="usuario123" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Contraseña</label>
                        <input type="password" name="password" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Rol de Usuario</label>
                        <select name="rol" class="form-select rounded-3">
                            <option value="cajero">Cajero (Ventas)</option>
                            <option value="admin">Administrador (Acceso total)</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">
                            <i class="bi bi-check-circle-fill me-2"></i>Guardar Usuario
                        </button>
                        <a href="listar.php" class="btn btn-light rounded-pill px-4">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>