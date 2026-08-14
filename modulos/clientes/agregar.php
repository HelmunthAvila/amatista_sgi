<?php
include("../../includes/sesion.php");
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");
?>

<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --bg-light-gray: #f8f9fa;
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
        <h2 class="fw-bold text-dark" style="letter-spacing: -0.5px;">Registrar Nuevo Cliente</h2>
        <p class="text-muted small">Complete los campos para dar de alta un cliente en el sistema.</p>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-7">
            <div class="card card-custom shadow-sm p-4">
                <form action="guardar.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">

                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="nombre" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;" placeholder="Nombre y apellido" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Teléfono / Celular</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telefono" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;" placeholder="Ej: 300 123 4567">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;" placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-amatista-primary rounded-pill py-3 shadow-sm">
                            <i class="bi bi-check-circle-fill me-2"></i>Guardar Cliente
                        </button>
                    </div>

                </form>
            </div>
        </div>
        
        <div class="col-xl-4 col-lg-5">
            <div class="card card-custom bg-white shadow-sm p-4 mt-4 mt-lg-0">
                <h6 class="fw-bold text-dark mb-2">
                    <i class="bi bi-info-circle text-primary me-2"></i>Información
                </h6>
                <p class="small text-muted mb-0">
                    Los datos registrados aquí estarán disponibles inmediatamente en el módulo de 
                    <strong>Ventas (POS)</strong> para facturación rápida.
                </p>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>