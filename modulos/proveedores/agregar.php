<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Registrar Nuevo Proveedor</h2>
            <p class="text-muted small">Añade fábricas o distribuidores mayoristas al SGI.</p>
        </div>

        <a href="listar.php" class="btn btn-light border rounded-pill px-4 shadow-sm">
            <i class="bi bi-arrow-left me-2"></i>Volver al Listado
        </a>
    </div>

    <div class="row">
        <div class="col-md-6 col-lg-5">
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    
                    <h5 class="fw-bold mb-4 text-primary">
                        <i class="bi bi-building-plus me-2"></i>Datos de Suministro
                    </h5>

                    <form action="guardar.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Empresa / Fábrica</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-building"></i></span>
                                <input type="text" name="empresa" class="form-control bg-light border-0" 
                                       placeholder="Ej. Calzado Vizzano" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nombre del Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-person"></i></span>
                                <input type="text" name="nombre" class="form-control bg-light border-0" 
                                       placeholder="Ej. Carlos Ruiz" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted text-uppercase">Teléfono / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-whatsapp"></i></span>
                                <input type="text" name="telefono" class="form-control bg-light border-0" 
                                       placeholder="Ej. 310 000 0000" required>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill shadow-sm py-2 fw-bold">
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
</div>

<?php 
// Incluye el pie de página del sistema
include("../../includes/footer.php"); 
?>