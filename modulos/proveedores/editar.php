<?php
include("../../includes/sesion.php");
// Incluir el archivo de conexión a la base de datos y el encabezado del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Obtener el ID del proveedor enviado por URL de forma segura
if (isset($_GET['id'])) {

    $id = mysqli_real_escape_string($conexion, $_GET['id']);

    // Consulta para obtener los datos del proveedor
    $consulta = mysqli_query($conexion, "SELECT * FROM proveedores WHERE id = '$id'");
    $p = mysqli_fetch_array($consulta);

    if (!$p) { 
        header("Location: listar.php"); 
        exit();
    }

} else {
    header("Location: listar.php");
    exit();
}
?>

<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --border-radius-card: 16px;
    }
    .btn-amatista-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-amatista-primary:hover {
        background-color: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
        transform: translateY(-1px);
    }
    .card-custom {
        border-radius: var(--border-radius-card) !important;
        border: none !important;
        background-color: #ffffff;
    }
    .form-control-custom {
        border-radius: 10px !important;
        border: 1px solid #ced4da !important;
        padding: 0.6rem 1rem;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(81, 45, 168, 0.15) !important;
    }
</style>

<div class="container-fluid px-4 py-3">

    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h2 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Editar Proveedor</h2>
        <p class="text-muted small">Actualiza la información de contacto o la empresa proveedora.</p>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-7">
            <div class="card card-custom shadow-sm p-4">
                <form action="actualizar.php" method="POST">

                    <input type="hidden" name="id" value="<?php echo $p['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Empresa / Fábrica</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-building"></i></span>
                            <input type="text" name="empresa" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   value="<?php echo htmlspecialchars($p['empresa']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Nombre de Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="nombre" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   value="<?php echo htmlspecialchars($p['nombre']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telefono" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   value="<?php echo htmlspecialchars($p['telefono']); ?>" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-amatista-primary rounded-pill px-4 py-2 shadow-sm">
                            <i class="bi bi-check-lg me-2"></i>Actualizar Datos
                        </button>
                        <a href="listar.php" class="btn btn-light rounded-pill px-4 py-2 border text-muted small">Cancelar</a>
                    </div>

                </form>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5 mt-4 mt-lg-0">
            <div class="card card-custom text-white shadow-sm h-100 p-4 text-center d-flex flex-column justify-content-center" style="background-color: var(--primary-color) !important;">
                <i class="bi bi-info-circle mb-3" style="font-size: 2.5rem; opacity: 0.9;"></i>
                <h5 class="fw-bold mb-2">Consejo de Gestión</h5>
                <p class="small mb-0 opacity-75">
                    Mantener los datos de contacto actualizados asegura una comunicación fluida con tus fabricantes de calzado.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>