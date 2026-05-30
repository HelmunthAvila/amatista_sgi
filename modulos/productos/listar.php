<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Captura los filtros enviados por la URL (GET)
$busqueda = $_GET['busqueda'] ?? '';
$filtro_stock = $_GET['filtro_stock'] ?? '';

// Define la consulta base para obtener los productos
$query = "SELECT * FROM productos WHERE 1=1";

// Aplica el filtro de búsqueda por texto (Nombre o Marca)
if (!empty($busqueda)) {
    $busqueda_escapada = mysqli_real_escape_string($conexion, $busqueda);
    $query .= " AND (nombre LIKE '%$busqueda_escapada%' OR marca LIKE '%$busqueda_escapada%')";
}

// Aplica el filtro por condición de Stock
if ($filtro_stock === 'bajo') {
    $query .= " AND stock <= 5";
} elseif ($filtro_stock === 'disponible') {
    $query .= " AND stock > 5";
}

// Ordena los productos alfabéticamente por nombre
$query .= " ORDER BY nombre ASC";

// Ejecuta la consulta
$productos = mysqli_query($conexion, $query);

if (!$productos) {
    die("Error en la consulta de inventario: " . mysqli_error($conexion));
}
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Inventario de Calzado</h2>
            <p class="text-muted small">Control detallado de existencias, tallas y modelos.</p>
        </div>

        <a href="agregar.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-plus-lg me-2"></i>Agregar Zapato
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="listar.php">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-5">
                        <label class="form-label small fw-bold text-muted text-uppercase">Buscar calzado</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busqueda" class="form-control bg-light border-0" 
                                   placeholder="Ej. Alpargata, Nike, Bosi..." value="<?php echo htmlspecialchars($busqueda); ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-bold text-muted text-uppercase">Disponibilidad (Stock)</label>
                        <select name="filtro_stock" class="form-select bg-light border-0">
                            <option value="">Todos los productos</option>
                            <option value="bajo" <?php echo $filtro_stock === 'bajo' ? 'selected' : ''; ?>>⚠️ Stock Crítico (5 unidades o menos)</option>
                            <option value="disponible" <?php echo $filtro_stock === 'disponible' ? 'selected' : ''; ?>>✅ Stock Estable (Más de 5 unidades)</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            Filtrar
                        </button>
                        <a href="listar.php" class="btn btn-light border w-100 rounded-pill">
                            <i class="bi bi-arrow-repeat"></i> Limpiar
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">

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

                <tbody>

                    <?php if (mysqli_num_rows($productos) > 0) { ?>
                        <?php while($p = mysqli_fetch_array($productos)){ 
                            // Define el color del indicador de stock (rojo si hay menos de 5 unidades)
                            $badge_color = ($p['stock'] <= 5) ? 'bg-danger' : 'bg-success';
                        ?>

                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-tag-fill"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?php echo $p['nombre']; ?></span>
                                </div>
                            </td>

                            <td>
                                <span class="text-muted"><?php echo $p['marca']; ?></span>
                            </td>

                            <td>
                                <span class="badge bg-light text-dark border me-1">
                                    Talla: <?php echo $p['talla']; ?>
                                </span>
                                <span class="badge bg-light text-dark border">
                                    <?php echo $p['color']; ?>
                                </span>
                            </td>

                            <td class="fw-bold text-dark">
                                $<?php echo number_format($p['precio']); ?>
                            </td>

                            <td>
                                <span class="badge <?php echo $badge_color; ?> px-2 py-1 rounded-pill">
                                    <?php echo $p['stock']; ?> unds.
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-3">
                                    <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white border border-end-0" title="Editar">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <a href="eliminar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white border" 
                                       onclick="return confirm('¿Está seguro de eliminar este producto del inventario?')" title="Eliminar">
                                        <i class="bi bi-trash text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-emoji-frown fs-2 d-block mb-2 text-secondary"></i>
                                No se encontraron productos que coincidan con los filtros aplicados.
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>