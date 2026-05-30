<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Consulta todos los productos ordenados alfabéticamente por nombre
$productos = mysqli_query($conexion, "SELECT * FROM productos ORDER BY nombre ASC");
?>

<!-- Contenedor principal del módulo de inventario -->
<div class="container-fluid">

    <!-- Encabezado del módulo con botón para agregar nuevo producto -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Inventario de Calzado</h2>
            <p class="text-muted small">Control detallado de existencias, tallas y modelos.</p>
        </div>

        <!-- Botón para abrir el formulario de registro de un nuevo zapato -->
        <a href="agregar.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Agregar Zapato
        </a>
    </div>

    <!-- Tarjeta que contiene la tabla de inventario -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- Tabla responsiva -->
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">

                <!-- Encabezado de columnas -->
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Producto</th>
                        <th class="text-uppercase small fw-bold text-muted">Marca</th>
                        <th class="text-uppercase small fw-bold text-muted">Talla / Color</th>
                        <th class="text-uppercase small fw-bold text-muted">Precio</th>
                        <th class="text-uppercase small fw-bold text-muted">Stock</th>
                        <th class="text-center text-uppercase small fw-bold text-muted">Acciones</th>
                    </tr>
                </thead>

                <!-- Cuerpo de la tabla -->
                <tbody>

                    <?php while($p = mysqli_fetch_array($productos)){ 
                        
                        // Define el color del indicador de stock (rojo si hay menos de 5 unidades)
                        $badge_color = ($p['stock'] <= 5) ? 'bg-danger' : 'bg-success';
                    ?>

                    <tr>

                        <!-- Columna del nombre del producto -->
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-light text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                    <i class="bi bi-tag-fill"></i>
                                </div>
                                <span class="fw-bold text-dark"><?php echo $p['nombre']; ?></span>
                            </div>
                        </td>

                        <!-- Columna marca -->
                        <td>
                            <span class="text-muted"><?php echo $p['marca']; ?></span>
                        </td>

                        <!-- Columna talla y color -->
                        <td>
                            <span class="badge bg-light text-dark border me-1">
                                Talla: <?php echo $p['talla']; ?>
                            </span>

                            <span class="badge bg-light text-dark border">
                                <?php echo $p['color']; ?>
                            </span>
                        </td>

                        <!-- Columna precio formateado -->
                        <td>
                            <span class="fw-bold">
                                $ <?php echo number_format($p['precio'], 2); ?>
                            </span>
                        </td>

                        <!-- Columna stock con alerta visual -->
                        <td>
                            <span class="badge <?php echo $badge_color; ?> rounded-pill px-3">
                                <?php echo $p['stock']; ?> und.
                            </span>
                        </td>

                        <!-- Columna acciones (editar / eliminar) -->
                        <td class="text-center">
                            <div class="btn-group shadow-sm rounded-3">

                                <!-- Botón editar producto -->
                                <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white border-end" title="Editar">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </a>

                                <!-- Botón eliminar producto -->
                                <a href="eliminar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white" 
                                   onclick="return confirm('¿Seguro que desea eliminar este calzado?')" title="Eliminar">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </a>

                            </div>
                        </td>

                    </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Incluye el pie de página del sistema
include("../../includes/footer.php"); 
?>