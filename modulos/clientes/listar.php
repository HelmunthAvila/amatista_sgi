<?php
include("../../includes/sesion.php");
// Iniciamos sesión al principio para renderizar las notificaciones instantáneas

// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// 1. Configurar la cantidad de registros por página
$por_pagina = 10;

// 2. Determinar la página actual
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina_actual < 1) { $pagina_actual = 1; }

// 3. Calcular el offset (inicio del límite para SQL)
$offset = ($pagina_actual - 1) * $por_pagina;

// Captura el filtro de búsqueda enviado por la URL (GET)
$busqueda = $_GET['busqueda'] ?? '';


// --- CONSULTA PARA CONTAR EL TOTAL DE REGISTROS (Necesario para la paginación) ---
$query_conteo = "SELECT COUNT(*) as total_registros FROM clientes WHERE 1=1";

if (!empty($busqueda)) {
    $busqueda_escapada = mysqli_real_escape_string($conexion, $busqueda);
    $query_conteo .= " AND (nombre LIKE '%$busqueda_escapada%' 
                             OR telefono LIKE '%$busqueda_escapada%' 
                             OR email LIKE '%$busqueda_escapada%')";
}

$resultado_conteo = mysqli_query($conexion, $query_conteo);
$fila_conteo = mysqli_fetch_assoc($resultado_conteo);
$total_registros = $fila_conteo['total_registros'];

// Calcular el total de páginas necesarias
$total_paginas = ceil($total_registros / $por_pagina);


// --- CONSULTA PRINCIPAL CON LIMIT Y OFFSET ---
$query = "SELECT * FROM clientes WHERE 1=1";

if (!empty($busqueda)) {
    $busqueda_escapada = mysqli_real_escape_string($conexion, $busqueda);
    $query .= " AND (nombre LIKE '%$busqueda_escapada%' 
                     OR telefono LIKE '%$busqueda_escapada%' 
                     OR email LIKE '%$busqueda_escapada%')";
}

// Ordena los clientes alfabéticamente por nombre con los límites de paginación
$query .= " ORDER BY nombre ASC LIMIT $por_pagina OFFSET $offset";

// Ejecuta la consulta
$clientes = mysqli_query($conexion, $query);

if (!$clientes) {
    die("Error en la consulta de clientes: " . mysqli_error($conexion));
}

// Conservar los filtros activos al cambiar de página
$params_busqueda = "";
if(!empty($busqueda)){ $params_busqueda .= "&busqueda=" . urlencode($busqueda); }
?>

<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
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

    /* Estilos personalizados para la paginación Amatista */
    .pagination .page-link {
        color: var(--primary-color);
        border: none;
        padding: 0.6rem 0.9rem;
        margin: 0 2px;
        border-radius: 8px;
    }
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color) !important;
        color: #fff !important;
    }
    .pagination .page-link:hover {
        background-color: #f0ebfa;
        color: var(--primary-hover);
    }
</style>

<div class="container-fluid px-4 py-3">

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
        <?php unset($_SESSION['alerta']); ?>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Directorio de Clientes</h2>
            <p class="text-muted small mb-0">Administra la información de contacto de tus compradores.</p>
        </div>

        <a href="agregar.php" class="btn btn-amatista-primary rounded-pill px-4 shadow-sm d-flex align-items-center">
            <i class="bi bi-person-plus-fill me-2 fs-5"></i> Registrar Cliente
        </a>
    </div>

    <div class="card card-custom shadow-sm mb-4">
        <div class="card-body p-4">
            <form method="GET" action="listar.php">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-9">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Buscar Cliente</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 border text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busqueda" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   placeholder="Buscar por nombre, teléfono o correo electrónico..." value="<?php echo htmlspecialchars($busqueda); ?>">
                        </div>
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

    <div class="card card-custom shadow-sm overflow-hidden mb-4">
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">
                <thead>
                    <tr class="table-custom-header border-bottom">
                        <th class="ps-4 py-3 border-0 small text-uppercase text-secondary">Nombre</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Contacto Directo</th>
                        <th class="py-3 border-0 small text-uppercase text-secondary">Email</th>
                        <th class="text-center py-3 border-0 small text-uppercase text-secondary">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($clientes) > 0) { ?>
                        <?php while($c = mysqli_fetch_array($clientes)){ 
                            $tel_limpio = str_replace([' ', '-', '.'], '', $c['telefono']);
                        ?>
                        <tr class="border-bottom">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; background-color: #f0ebfa !important; color: var(--primary-color) !important;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?php echo htmlspecialchars($c['nombre']); ?></span>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <?php if(!empty($c['telefono'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($tel_limpio); ?>" class="text-decoration-none text-dark mb-1 small fw-semibold">
                                            <i class="bi bi-telephone-fill text-primary me-2"></i><?php echo htmlspecialchars($c['telefono']); ?>
                                        </a>
                                        <a href="https://wa.me/57<?php echo htmlspecialchars($tel_limpio); ?>" target="_blank" class="text-success text-decoration-none small fw-bold d-flex align-items-center">
                                            <i class="bi bi-whatsapp me-1"></i> Enviar Mensaje
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted small">Sin teléfono</span>
                                    <?php endif; ?>
                                </div>
                            </td>

                            <td>
                                <?php if(!empty($c['email'])): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($c['email']); ?>" class="text-secondary text-decoration-none small">
                                        <i class="bi bi-envelope-fill me-2 text-muted"></i><?php echo htmlspecialchars($c['email']); ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Sin e-mail</span>
                                <?php endif; ?>
                            </td>

                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    <a href="editar.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-white bg-white border-end" title="Editar Cliente">
                                        <i class="bi bi-pencil-square text-primary fs-6"></i>
                                    </a>
                                    <a href="eliminar.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-white bg-white" 
                                       onclick="return confirm('¿Está seguro de eliminar a este cliente de Amatista SGI?')" title="Eliminar Cliente">
                                        <i class="bi bi-trash3 text-danger fs-6"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x display-6 d-block mb-3 text-light"></i>
                                No se encontraron clientes que coincidan con la búsqueda aplicada.
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- CONTROL DE PAGINACIÓN -->
    <?php if($total_paginas > 1): ?>
        <div class="d-flex justify-content-between align-items-center px-2">
            <div class="text-muted small">
                Mostrando página <strong><?php echo $pagina_actual; ?></strong> de <strong><?php echo $total_paginas; ?></strong> (Total de clientes: <?php echo $total_registros; ?>)
            </div>
            <nav aria-label="Navegación de clientes">
                <ul class="pagination pagination-sm mb-0 shadow-sm rounded-3 bg-white p-1">
                    <!-- Botón Anterior -->
                    <li class="page-item <?php echo ($pagina_actual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1 . $params_busqueda; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Anterior</span>
                        </a>
                    </li>

                    <!-- Páginas numéricas -->
                    <?php 
                    $rango = 2; 
                    for($i = 1; $i <= $total_paginas; $i++): 
                        if ($i == 1 || $i == $total_paginas || ($i >= $pagina_actual - $rango && $i <= $pagina_actual + $rango)):
                    ?>
                        <li class="page-item <?php echo ($pagina_actual == $i) ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i . $params_busqueda; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php 
                        endif;
                    endfor; 
                    ?>

                    <!-- Botón Siguiente -->
                    <li class="page-item <?php echo ($pagina_actual >= $total_paginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1 . $params_busqueda; ?>" aria-label="Next">
                            <span aria-hidden="true">Siguiente &raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const alerta = document.getElementById('alerta-automatica');
        if (alerta) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alerta);
                bsAlert.close();
            }, 2000);
        }
    });
</script>

<?php include("../../includes/footer.php"); ?>