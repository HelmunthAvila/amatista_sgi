<?php
// Incluye la conexión a la base de datos y el encabezado del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Obtiene el ID del producto enviado por la URL
$id = $_GET['id'];

// Consulta el producto específico en la base de datos
$query = mysqli_query($conexion, "SELECT * FROM productos WHERE id = $id");

// Guarda los datos del producto en un arreglo
$p = mysqli_fetch_array($query);
?>

<!-- Contenedor principal del módulo -->
<div class="container-fluid">

    <!-- Encabezado del formulario de edición -->
    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2">
            <i class="bi bi-arrow-left me-1"></i> Volver al inventario
        </a>

        <!-- Título del formulario -->
        <h2 class="fw-bold text-dark">Editar Zapato</h2>

        <!-- Descripción con el nombre del producto -->
        <p class="text-muted small">
            Actualice las especificaciones del producto: 
            <strong><?= $p['nombre'] ?></strong>
        </p>
    </div>

    <!-- Contenedor del formulario -->
    <div class="row">
        <div class="col-xl-7 col-lg-9">

            <!-- Tarjeta visual del formulario -->
            <div class="card border-0 shadow-sm rounded-4 p-4">

                <!-- Formulario que enviará los cambios al archivo actualizar.php -->
                <form action="actualizar.php" method="POST">

                    <!-- Campo oculto que envía el ID del producto -->
                    <input type="hidden" name="id" value="<?= $p['id'] ?>">

                    <div class="row g-3">

                        <!-- Campo nombre o modelo del zapato -->
                        <div class="col-md-12 mb-2">
                            <label class="form-label fw-bold small text-uppercase text-muted">Modelo / Nombre</label>
                            <input type="text" name="nombre" class="form-control form-control-lg bg-light border-0" 
                                   value="<?= $p['nombre'] ?>" required>
                        </div>

                        <!-- Campo marca del producto -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Marca</label>
                            <input type="text" name="marca" class="form-control bg-light border-0" 
                                   value="<?= $p['marca'] ?>" placeholder="Ej: Nike">
                        </div>

                        <!-- Campo talla del zapato -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Talla</label>
                            <input type="text" name="talla" class="form-control bg-light border-0" 
                                   value="<?= $p['talla'] ?>" placeholder="Ej: 38">
                        </div>

                        <!-- Campo color del producto -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold small text-uppercase text-muted">Color</label>
                            <input type="text" name="color" class="form-control bg-light border-0" 
                                   value="<?= $p['color'] ?>" placeholder="Ej: Negro">
                        </div>

                        <!-- Campo precio de venta -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Precio de Venta ($)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary fw-bold">$</span>
                                <input type="number" step="0.01" name="precio" class="form-control form-control-lg bg-light border-0" 
                                       value="<?= $p['precio'] ?>" required>
                            </div>
                        </div>

                        <!-- Campo cantidad de stock disponible -->
                        <div class="col-md-6 mt-3">
                            <label class="form-label fw-bold small text-uppercase text-muted">Existencias (Stock)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0 text-primary fw-bold">
                                    <i class="bi bi-box-seam"></i>
                                </span>
                                <input type="number" name="stock" class="form-control form-control-lg bg-light border-0" 
                                       value="<?= $p['stock'] ?>" required>
                            </div>
                        </div>

                    </div>

                    <!-- Botón para guardar los cambios -->
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm py-3">
                            <i class="bi bi-save-fill me-2"></i>Actualizar Producto
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