<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");
?>

<!-- Contenedor principal del módulo de productos -->
<div class="container-fluid">

    <!-- Encabezado del formulario con enlace para regresar al inventario -->
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al inventario
        </a>

        <!-- Título del formulario -->
        <h2 class="fw-bold text-dark">Registrar Nuevo Zapato</h2>

        <!-- Descripción del formulario -->
        <p class="text-muted small">Ingrese los detalles del nuevo modelo para sumarlo al stock.</p>
    </div>

    <!-- Contenedor del formulario -->
    <div class="row">
        <div class="col-xl-7 col-lg-9">

            <!-- Tarjeta visual que contiene el formulario -->
            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- Formulario que envía los datos al archivo guardar.php -->
                <form action="guardar.php" method="POST">
                    
                    <div class="row g-3">

                        <!-- Campo para el nombre o modelo del zapato -->
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-bold small text-uppercase text-muted">Modelo / Nombre</label>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light border-0" 
                                   placeholder="Ej: Air Max 90" required>
                        </div>

                        <!-- Campo para registrar la marca -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Marca</label>
                            <input type="text" name="marca" class="form-control bg-light border-0" 
                                   placeholder="Ej: Nike">
                        </div>

                        <!-- Campo para registrar la talla -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Talla</label>
                            <input type="text" name="talla" class="form-control bg-light border-0" 
                                   placeholder="Ej: 40">
                        </div>

                        <!-- Campo para registrar el color -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Color</label>
                            <input type="text" name="color" class="form-control bg-light border-0" 
                                   placeholder="Ej: Blanco/Azul">
                        </div>

                        <!-- Campo para registrar el precio de venta -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Precio de Venta ($)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary fw-bold">$</span>
                                <input type="number" step="0.01" name="precio" class="form-control form-control-lg bg-light border-0" 
                                       placeholder="0.00" required>
                            </div>
                        </div>

                        <!-- Campo para registrar el stock inicial del producto -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Stock Inicial</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary fw-bold"><i class="bi bi-box-seam"></i></span>
                                <input type="number" name="stock" class="form-control form-control-lg bg-light border-0" 
                                       placeholder="Cantidad" required>
                            </div>
                        </div>

                    </div>

                    <!-- Botón para guardar el producto en la base de datos -->
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
// Incluye el pie de página del sistema
include("../../includes/footer.php"); 
?>