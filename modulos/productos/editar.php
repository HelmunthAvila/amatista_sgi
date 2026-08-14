<?php
include("../../includes/sesion.php");
// Incluye la conexión a la base de datos y el encabezado del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Obtiene el ID del producto enviado por la URL y lo sanea de inmediato
$id = mysqli_real_escape_string($conexion, $_GET['id']);

// Consulta el producto específico en la base de datos
$query = mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id");

// Guarda los datos del producto en un arreglo
$p = mysqli_fetch_array($query);
?>

<!-- Estilos unificados Amatista SGI -->
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

    <!-- Encabezado del formulario de edición -->
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2 d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-1 fs-5"></i> Volver al inventario
        </a>

        <!-- Título del formulario -->
        <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Editar Zapato</h2>
        <p class="text-muted small">
            Modificando especificaciones operativas para: <strong class="text-amatista"><?= htmlspecialchars($p['nombre']) ?></strong>
        </p>
    </div>

    <!-- Contenedor del formulario -->
    <div class="row">
        <div class="col-xl-7 col-lg-9">

            <!-- Tarjeta visual del formulario -->
            <div class="card card-custom shadow-sm p-4">

                <!-- Formulario que enviará los cambios al archivo actualizar.php -->
                <form action="actualizar.php" method="POST">

                    <!-- Campo oculto que envía el ID del producto -->
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                    <div class="row g-3">

                        <!-- Campo nombre o modelo del zapato -->
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Modelo / Nombre</label>
                            <input type="text" name="nombre" class="form-control form-control-custom fw-semibold" 
                                   value="<?= htmlspecialchars($p['nombre']) ?>" required>
                        </div>

                        <!-- Campo marca del producto -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Marca</label>
                            <input type="text" name="marca" class="form-control form-control-custom" 
                                   value="<?= htmlspecialchars($p['marca']) ?>" required>
                        </div>

                        <!-- Campo talla del zapato -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Talla</label>
                            <input type="text" name="talla" class="form-control form-control-custom" 
                                   value="<?= htmlspecialchars($p['talla']) ?>" required>
                        </div>

                        <!-- Campo color del producto -->
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Color</label>
                            <input type="text" name="color" class="form-control form-control-custom" 
                                   value="<?= htmlspecialchars($p['color']) ?>" required>
                        </div>

                        <!-- Campo precio de venta -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Precio de Venta ($)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom">$</span>
                                <input type="number" step="1" name="precio" class="form-control form-control-custom" 
                                       style="border-radius: 0 10px 10px 0 !important;" value="<?= $p['precio'] ?>" required>
                            </div>
                        </div>

                        <!-- Campo cantidad de stock disponible -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-semibold small text-uppercase text-secondary">Existencias (Stock)</label>
                            <div class="input-group">
                                <span class="input-group-text input-group-text-custom"><i class="bi bi-box-seam"></i></span>
                                <input type="number" name="stock" class="form-control form-control-custom" 
                                       style="border-radius: 0 10px 10px 0 !important;" value="<?= $p['stock'] ?>" required>
                            </div>
                        </div>

                    </div>

                    <!-- Botón para guardar los cambios -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-amatista-primary btn-lg rounded-pill shadow-sm py-2-5">
                            <i class="bi bi-save-fill me-2"></i>Actualizar Cambios
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