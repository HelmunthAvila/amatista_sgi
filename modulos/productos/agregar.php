<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");
?>

<!-- Estilos integrados para mantener la consistencia Amatista SGI -->
<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --bg-light-gray: #f8f9fa;
        --border-radius-card: 16px;
    }

    .text-amatista {
        color: var(--primary-color) !important;
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
        padding: 0.65rem 1rem;
        background-color: var(--bg-light-gray) !important;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color) !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 0.25rem rgba(81, 45, 168, 0.15) !important;
    }
    
    .input-group-text-custom {
        background-color: #f0ebfa !important;
        color: var(--primary-color) !important;
        border: none !important;
        border-radius: 10px 0 0 10px !important;
        font-weight: 600;
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- Encabezado del formulario con enlace para regresar al inventario -->
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2 d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-1 fs-5"></i> Volver al inventario
        </a>

        <!-- Título del formulario -->
        <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Registrar Nuevo Zapato</h2>
        <p class="text-muted small">Ingrese los detalles del nuevo modelo para sumarlo al stock activo.</p>
    </div>

    <!-- Contenedor del formulario -->
    <div class="row">
        <div class="col-xl-7 col-lg-9">

            <!-- Tarjeta visual que contiene el formulario -->
            <div class="card card-custom shadow-sm p-4">

                <!-- Formulario que envía los datos al archivo guardar.php -->
                <form action="guardar.php" method="POST">
                    
                    <div class="row g-3">

                        <!-- Campo para el nombre o modelo del zapato -->
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Modelo / Nombre</label>
                            <input type="text" name="nombre" class="form-control form-control-custom fw-semibold" 
                                   placeholder="Ej: Air Max 90" required>
                        </div>

                        <!-- Campo para registrar la marca -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Marca</label>
                            <input type="text" name="marca" class="form-control form-control-custom" 
                                   placeholder="Ej: Nike" required>
                        </div>

                        <!-- Campo para registrar la talla -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Talla</label>
                            <input type="text" name="talla" class="form-control form-control-custom" 
                                   placeholder="Ej: 40" required>
                        </div>

                        <!-- Campo para registrar el color -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Color</label>
                            <input type="text" name="color" class="form-control form-control-custom" 
                                   placeholder="Ej: Blanco/Azul" required>
                        </div>

                        <!-- Campo para registrar el precio de venta -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Precio de Venta ($)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom">$</span>
                                <input type="number" step="1" name="precio" class="form-control form-control-custom" 
                                       style="border-radius: 0 10px 10px 0 !important;" placeholder="0" required>
                            </div>
                        </div>

                        <!-- Campo para registrar el stock inicial del producto -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Stock Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom"><i class="bi bi-box-seam"></i></span>
                                <input type="number" name="stock" class="form-control form-control-custom" 
                                       style="border-radius: 0 10px 10px 0 !important;" placeholder="Cantidad" required>
                            </div>
                        </div>

                    </div>

                    <!-- Botón para guardar el producto en la base de datos -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-amatista-primary btn-lg rounded-pill shadow-sm py-2-5">
                            <i class="bi bi-plus-circle-fill me-2"></i>Guardar en Inventario
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