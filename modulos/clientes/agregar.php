<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");
?>

<!-- Contenedor principal del módulo de registro de clientes -->
<div class="container-fluid">

    <!-- Encabezado del módulo con botón para regresar al listado -->
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

        <!-- Título del formulario -->
        <h2 class="fw-bold text-dark">Registrar Nuevo Cliente</h2>

        <!-- Descripción breve del proceso -->
        <p class="text-muted small">Complete los campos para dar de alta un cliente en el sistema.</p>
    </div>

    <div class="row">

        <!-- Columna donde se encuentra el formulario -->
        <div class="col-xl-5 col-lg-7">

            <!-- Tarjeta que contiene el formulario de registro -->
            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- Formulario que envía los datos al archivo guardar.php -->
                <form action="guardar.php" method="POST">
                    
                    <!-- Campo para ingresar el nombre completo del cliente -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-person text-primary"></i>
                            </span>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light border-0" 
                                   placeholder="Nombre y apellido" required>
                        </div>
                    </div>

                    <!-- Campo para ingresar el número de teléfono -->
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-uppercase text-muted">Teléfono / Celular</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-telephone text-primary"></i>
                            </span>
                            <input type="text" name="telefono" class="form-control form-control-lg bg-light border-0" 
                                   placeholder="Ej: 300 123 4567">
                        </div>
                    </div>

                    <!-- Campo para ingresar el correo electrónico del cliente -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-uppercase text-muted">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0">
                                <i class="bi bi-envelope text-primary"></i>
                            </span>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0" 
                                   placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    <!-- Botón para guardar el cliente en la base de datos -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3">
                            <i class="bi bi-check-circle-fill me-2"></i>Guardar Cliente
                        </button>
                    </div>

                </form>
            </div>
        </div>
        
        <!-- Columna con información adicional para el usuario -->
        <div class="col-xl-4 col-lg-5">

            <!-- Tarjeta informativa sobre el uso de los datos -->
            <div class="alert bg-white border-0 shadow-sm rounded-4 p-4 mt-4 mt-lg-0">

                <h6 class="fw-bold">
                    <i class="bi bi-info-circle text-primary me-2"></i>Información
                </h6>

                <!-- Explica dónde se utilizarán los datos registrados -->
                <p class="small text-muted mb-0">
                    Los datos registrados aquí estarán disponibles inmediatamente en el módulo de 
                    <strong>Ventas (POS)</strong> para facturación rápida.
                </p>

            </div>
        </div>
    </div>
</div>

<?php 
// Incluye el pie de página del sistema
include("../../includes/footer.php"); 
?>