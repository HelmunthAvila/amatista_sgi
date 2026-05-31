<?php
// Iniciamos sesión al principio para renderizar las notificaciones instantáneas
session_start();

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

<!-- Estilos unificados basados en la identidad visual de Amatista SGI -->
<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --accent-success: #2e7d32;
        --accent-danger: #c62828;
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

    .btn-amatista-secondary {
        background-color: #f1f3f5 !important;
        border: 1px solid #dee2e6 !important;
        color: #495057 !important;
        font-weight: 600;
    }
    .btn-amatista-secondary:hover {
        background-color: #e9ecef !important;
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

    .table-custom-header {
        background-color: #f8f9fa !important;
        color: #495057;
        font-weight: 600;
    }
    
    .table-hover tbody tr:hover {
        background-color: #fcfbfe !important;
    }

    /* Badges de Talla y Color en tonos pastel */
    .badge-pastel-purple {
        background-color: #f0ebfa !important;
        color: var(--primary-color) !important;
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }
    .badge-pastel-gray {
        background-color: #f1f3f5 !important;
        color: #495057 !important;
        font-weight: 600;
        padding: 0.5em 0.8em;
        border-radius: 6px;
    }

    /* Estados de stock corporativos */
    .badge-stock-success {
        background-color: #e8f5e9 !important;
        color: var(--accent-success) !important;
        font-weight: bold;
        padding: 0.5em 0.85em;
    }
    .badge-stock-danger {
        background-color: #ffebee !important;
        color: var(--accent-danger) !important;
        font-weight: bold;
        padding: 0.5em 0.85em;
    }
</style>

<div class="container-fluid px-4 py-3">

    <!-- ALERTA INTELIGENTE CON TEMPORIZADOR DE CIERRE -->
    <?php if(isset($_SESSION['alerta'])): ?>
        <div id="alerta-automatica" class="alert alert-<?php echo $_SESSION['alerta']['tipo']; ?> alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4 p-3" role="alert">
            <div class="d-flex align-items-center">
                <?php if($_SESSION['alerta']['tipo'] == 'success'): ?>
                    <i class="bi bi-check-circle-fill me-3 fs-4 text-success"></i>
                <?php else: ?>
                    <i class="bi bi-exclamation-triangle-fill me-3 fs-4 text-danger"></i>
                <?php endif; ?>
                <div>
                    <?php echo $_SESSION['alerta']['mensaje']; ?>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['alerta']); // Limpia la variable para evitar duplicaciones ?>
    <?php endif; ?>

    <!-- Encabezado del Módulo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Inventario de Calzado</h2>
            <p class="text-muted small mb-0">Control detallado de existencias, tallas y modelos del almacén.</p>
        </div>

        <a href="agregar.php" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm d-flex align-items-center">
            <i class="bi bi-plus-lg me-2 fw-bold"></i> Agregar Zapato
        </a>
    </div>

    <!-- Filtros Inteligentes -->
    <div class="card card-custom shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="listar.php">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Buscar calzado</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 border text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busqueda" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   placeholder="Ej. Alpargata, Nike, Bosi..." value="<?php echo htmlspecialchars($busqueda); ?>">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Disponibilidad (Stock)</label>
                        <select name="filtro_stock" class="form-select form-control-custom">
                            <option value="">Todos los productos</option>
                            <option value="bajo" <?php echo $filtro_stock === 'bajo' ? 'selected' : ''; ?>>⚠️ Stock Crítico (5 unidades o menos)</option>
                            <option value="disponible" <?php echo $filtro_stock === 'disponible' ? 'selected' : ''; ?>>✅ Stock Estable (Más de 5 unidades)</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-amatista-primary w-100 rounded-pill py-2 shadow-sm">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                        <a href="listar.php" class="btn btn-amatista-secondary w-100 rounded-pill py-2 d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-repeat me-1"></i> Limpiar
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <!-- Tabla Maestra de Productos -->
    <div class="card card-custom shadow-sm overflow-hidden">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead>
                    <tr class="table-custom-header border-bottom">
                        <th class="ps-4 py-3 border-0 small text-uppercase text-secondary">Producto</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Marca</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Talla / Color</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Precio</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Stock Activo</th>
                        <th class="text-center py-3 border-0 small text-uppercase text-secondary">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($productos) > 0) { ?>
                        <?php while($p = mysqli_fetch_array($productos)){ 
                            // Evaluación dinámica de los estados de stock
                            $badge_class = ($p['stock'] <= 5) ? 'badge-stock-danger' : 'badge-stock-success';
                            $icono_stock = ($p['stock'] <= 5) ? '⚠️ Crítico:' : '✅ Estable:';
                        ?>
                        <tr class="border-bottom">
                            <!-- Producto -->
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #f0ebfa !important; color: var(--primary-color) !important;">
                                        <i class="bi bi-tag-fill fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($p['nombre']); ?></span>
                                </div>
                            </td>

                            <!-- Marca -->
                            <td>
                                <span class="text-secondary fw-semibold"><?php echo htmlspecialchars($p['marca']); ?></span>
                            </td>

                            <!-- Talla / Color -->
                            <td>
                                <span class="badge badge-pastel-purple me-1">
                                    T: <?php echo htmlspecialchars($p['talla']); ?>
                                </span>
                                <span class="badge badge-pastel-gray">
                                    <?php echo htmlspecialchars($p['color']); ?>
                                </span>
                            </td>

                            <!-- Precio Formateado -->
                            <td class="fw-bold text-dark">
                                $<?php echo number_format($p['precio'], 0, ',', '.'); ?>
                            </td>

                            <!-- Stock Dinámico -->
                            <td>
                                <span class="badge <?php echo $badge_class; ?> rounded-pill">
                                    <?php echo $icono_stock; ?> <?php echo $p['stock']; ?> unds.
                                </span>
                            </td>

                            <!-- Botonera de Acciones -->
                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white bg-white border-end" title="Editar Par">
                                        <i class="bi bi-pencil-square text-primary fs-6"></i>
                                    </a>
                                    <a href="eliminar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white bg-white" 
                                       onclick="return confirm('¿Está seguro de eliminar este producto de Amatista SGI?')" title="Eliminar Par">
                                        <i class="bi bi-trash3 text-danger fs-6"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="bi bi-clipboard-x display-6 d-block mb-3 text-light"></i>
                                No se encontraron productos que coincidan con los filtros aplicados actualmente.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- CONTROL TEMPORAL AUTOMÁTICO DE LAS NOTIFICACIONES (2 SEGUNDOS) -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alerta = document.getElementById('alerta-automatica');
        if (alerta) {
            setTimeout(() => {
                // Instancia la alerta nativa de Bootstrap y la cierra de forma fluida
                const bsAlert = new bootstrap.Alert(alerta);
                bsAlert.close();
            }, 2000); // 2000 milisegundos = 2 segundos exactos
        }
    });
</script>

<?php include("../../includes/footer.php"); ?>