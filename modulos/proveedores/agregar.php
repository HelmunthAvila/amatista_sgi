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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Registrar Nuevo Proveedor</h2>
            <p class="text-muted small">Añade fábricas o distribuidores mayoristas al SGI.</p>
        </div>

        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 small fw-semibold">
            <i class="bi bi-arrow-left me-2"></i>Volver al Listado
        </a>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-7">
            
            <div class="card card-custom shadow-sm p-4">
                <h5 class="fw-bold mb-4" style="color: var(--primary-color);">
                    <i class="bi bi-building-plus me-2"></i>Datos de Suministro
                </h5>

                <form action="guardar.php" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo csrf_token(); ?>">


                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Empresa / Fábrica</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-building"></i></span>
                            <input type="text" name="empresa" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   placeholder="Ej. Calzado Vizzano" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Nombre del Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="nombre" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   placeholder="Ej. Carlos Ruiz" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Teléfono / WhatsApp</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-whatsapp"></i></span>
                            <input type="text" name="telefono" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   placeholder="Ej. 310 000 0000" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-amatista-primary rounded-pill py-3 shadow-sm">
                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>Guardar Proveedor
                        </button>
                        <a href="listar.php" class="btn btn-light rounded-pill border py-2 text-muted small">
                            Cancelar Registro
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>