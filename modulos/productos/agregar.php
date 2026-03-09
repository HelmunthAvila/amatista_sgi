<?php
// 1. Inclusión de base y diseño
include("../../conexion.php");
include("../../includes/header.php");
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al inventario
        </a>
        <h2 class="fw-bold text-dark">Registrar Nuevo Zapato</h2>
        <p class="text-muted small">Ingrese los detalles del nuevo modelo para sumarlo al stock.</p>
    </div>

    <div class="row">
        <div class="col-xl-7 col-lg-9">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <form action="guardar.php" method="POST">
                    
                    <div class="row g-3">
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-bold small text-uppercase text-muted">Modelo / Nombre</label>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light border-0" 
                                   placeholder="Ej: Air Max 90" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Marca</label>
                            <input type="text" name="marca" class="form-control bg-light border-0" 
                                   placeholder="Ej: Nike">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Talla</label>
                            <input type="text" name="talla" class="form-control bg-light border-0" 
                                   placeholder="Ej: 40">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Color</label>
                            <input type="text" name="color" class="form-control bg-light border-0" 
                                   placeholder="Ej: Blanco/Azul">
                        </div>

                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Precio de Venta ($)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary fw-bold">$</span>
                                <input type="number" step="0.01" name="precio" class="form-control form-control-lg bg-light border-0" 
                                       placeholder="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Stock Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary fw-bold"><i class="bi bi-box-seam"></i></span>
                                <input type="number" name="stock" class="form-control form-control-lg bg-light border-0" 
                                       placeholder="Cantidad" required>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3">
                            <i class="bi bi-plus-circle-fill me-2"></i>Guardar en Inventario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
// 3. Cierre del layout
include("../../includes/footer.php"); 
?>