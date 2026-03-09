<?php
// 1. Inclusión de base y diseño
include("../../conexion.php");
include("../../includes/header.php");

// 2. Obtener el ID y consultar los datos actuales
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conexion, $_GET['id']);
    $consulta = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id = '$id'");
    $p = mysqli_fetch_array($consulta);

    // Si el proveedor no existe, volver al listado
    if (!$p) { header("Location: listar.php"); }
} else {
    header("Location: listar.php");
}
?>

<div class="container-fluid">
    <div class="mb-4">
        <h2 class="fw-bold mb-0 text-dark">Editar Proveedor</h2>
        <p class="text-muted small">Actualiza la información de contacto o la empresa proveedora.</p>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <form action="actualizar.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Nombre de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombre" class="form-control bg-light border-0" 
                                       value="<?php echo $p['nombre']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted small text-uppercase">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control bg-light border-0" 
                                       value="<?php echo $p['telefono']; ?>" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted small text-uppercase">Empresa / Fábrica</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="bi bi-building"></i></span>
                                <input type="text" name="empresa" class="form-control bg-light border-0" 
                                       value="<?php echo $p['empresa']; ?>" required>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                <i class="bi bi-check-lg me-2"></i>Actualizar Datos
                            </button>
                            <a href="listar.php" class="btn btn-light rounded-pill px-4">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 bg-primary text-white rounded-4 shadow-sm h-100">
                <div class="card-body p-4 d-flex flex-column justify-content-center text-center">
                    <i class="bi bi-info-circle mb-3" style="font-size: 2rem;"></i>
                    <h5 class="fw-bold">Consejo de Gestión</h5>
                    <p class="small opacity-75">Mantener los datos de contacto actualizados asegura una comunicación fluida con tus fabricantes de calzado.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// 3. Cierre del layout
include("../../includes/footer.php"); 
?>