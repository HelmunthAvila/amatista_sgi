<?php
require_once("includes/iniciar_sesion.php");

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// El cajero opera únicamente en el POS: el dashboard es exclusivo de administración
if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'admin') {
    header("Location: modulos/ventas/pos.php");
    exit();
}

require_once("conexion.php");
require_once("includes/configuracion.php");
include("includes/header.php");

/* --- LÓGICA DE DATOS --- */
function obtenerDato($conexion, $sql) {
    $resultado = mysqli_query($conexion, $sql);
    return mysqli_fetch_assoc($resultado);
}

// Indicadores maestros
$ventas_dia = obtenerDato($conexion, "SELECT SUM(total) as total FROM ventas WHERE DATE(fecha)=CURDATE() AND estado = 1");
$ventas_mes = obtenerDato($conexion, "SELECT SUM(total) as total FROM ventas WHERE MONTH(fecha)=MONTH(CURDATE()) AND YEAR(fecha)=YEAR(CURDATE()) AND estado = 1");
$productos  = obtenerDato($conexion, "SELECT COUNT(*) as total FROM productos WHERE estado = 1");
$clientes   = obtenerDato($conexion, "SELECT COUNT(*) as total FROM clientes WHERE estado = 1");

// Stock Crítico
$stock_minimo = obtener_stock_minimo($conexion);
$stock_bajo = mysqli_query($conexion, "SELECT nombre, marca, talla, stock FROM productos WHERE stock <= " . $stock_minimo . " AND estado = 1 ORDER BY stock ASC LIMIT 5");

// Top vendidos
$top_vendidos = mysqli_query($conexion, "
    SELECT p.nombre, p.marca, SUM(dv.cantidad) as total_vendido 
    FROM detalle_venta dv
    JOIN productos p ON dv.id_producto = p.id
    JOIN ventas v ON dv.id_venta = v.id AND v.estado = 1
    GROUP BY dv.id_producto
    ORDER BY total_vendido DESC
    LIMIT 5
");

/* --- PROCESAMIENTO DE DATOS PARA EL GRÁFICO (Consultas reales a BD, AM-015) --- */

// Semanal: ventas por día de la semana actual (Lun a Dom)
$datos_semanal = ['labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'], 'data' => array_fill(0, 7, 0)];
$res_semanal = mysqli_query($conexion, "SELECT DAYOFWEEK(fecha) as dia, SUM(total) as total 
    FROM ventas WHERE YEARWEEK(fecha, 1) = YEARWEEK(CURDATE(), 1) AND estado = 1 GROUP BY DAYOFWEEK(fecha)");
if ($res_semanal) {
    while ($f = mysqli_fetch_assoc($res_semanal)) {
        // DAYOFWEEK de MySQL: 1=Dom ... 7=Sáb -> índice 0=Lun ... 6=Dom
        $idx = ((int)$f['dia'] + 5) % 7;
        $datos_semanal['data'][$idx] = (float)$f['total'];
    }
}

// Mensual: ventas por semana del mes actual (Sem 1 .. Sem N)
$dias_mes_actual = (int)date('t');
$num_semanas = (int)ceil($dias_mes_actual / 7);
$datos_mensual = ['labels' => [], 'data' => []];
for ($s = 1; $s <= $num_semanas; $s++) {
    $datos_mensual['labels'][] = 'Sem ' . $s;
    $datos_mensual['data'][] = 0;
}
$res_mensual = mysqli_query($conexion, "SELECT CEIL(DAY(fecha)/7) as semana, SUM(total) as total 
    FROM ventas WHERE MONTH(fecha) = MONTH(CURDATE()) AND YEAR(fecha) = YEAR(CURDATE()) AND estado = 1 GROUP BY CEIL(DAY(fecha)/7)");
if ($res_mensual) {
    while ($f = mysqli_fetch_assoc($res_mensual)) {
        $idx = (int)$f['semana'] - 1;
        if ($idx >= 0 && $idx < $num_semanas) {
            $datos_mensual['data'][$idx] = (float)$f['total'];
        }
    }
}

// Anual: ventas por mes del año en curso (Ene-Dic)
$datos_anual = [
    'labels' => ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    'data'   => array_fill(0, 12, 0)
];
$res_anual = mysqli_query($conexion, "SELECT MONTH(fecha) as mes, SUM(total) as total 
    FROM ventas WHERE YEAR(fecha) = YEAR(CURDATE()) AND estado = 1 GROUP BY MONTH(fecha)");
if ($res_anual) {
    while ($f = mysqli_fetch_assoc($res_anual)) {
        $idx = (int)$f['mes'] - 1;
        if ($idx >= 0 && $idx < 12) {
            $datos_anual['data'][$idx] = (float)$f['total'];
        }
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container-fluid mt-4">

    <!-- Header / Selector de Usuario -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 fw-bold text-dark mb-0">Inicio - Amatista SGI</h1>
            <p class="text-muted small mb-0">Resumen operativo y analítica de calzado.</p>
        </div>

        <div class="dropdown">
            <button class="btn btn-white shadow-sm rounded-pill px-4 py-2 border dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2" style="color: #6f42c1 !important;"></i>
                <span class="text-muted me-2">Hola,</span>
                <strong class="text-dark"><?php echo htmlspecialchars($_SESSION['nombre_usuario'] ?? 'Usuario'); ?></strong>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 mt-2">
                <?php if(isset($_SESSION['rol']) && $_SESSION['rol'] == 'admin'): ?>
                <li>
                    <a class="dropdown-item py-2" href="modulos/usuarios/listar.php">
                        <i class="bi bi-shield-lock me-2" style="color: #6f42c1 !important;"></i> Gestionar Usuarios
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <?php endif; ?>
                <li>
                    <a class="dropdown-item py-2 text-danger" href="logout.php">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Indicadores KPI -->
    <div class="row g-4 mb-4">
        <?php 
        $cards = [
            ["Ventas Hoy", $ventas_dia['total'] ?? 0, "bi-cash-stack", "moneda"],
            ["Ventas Mes", $ventas_mes['total'] ?? 0, "bi-graph-up", "moneda"],
            ["Catálogo", $productos['total'] ?? 0, "bi-box-seam", "cantidad"],
            ["Mis Clientes", $clientes['total'] ?? 0, "bi-people", "cantidad"]
        ];

        foreach ($cards as $c) {
            if ($c[3] === "moneda") {
                $valor_formateado = '$' . number_format($c[1], 0, ",", ".");
            } else {
                $valor_formateado = number_format($c[1], 0, ",", ".") . ' <span class="fs-6 fw-normal text-muted">und.</span>';
            }

            echo '<div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white border-start border-4" style="border-color: #6f42c1 !important;">
                        <div class="card-body d-flex align-items-center p-3">
                            <div class="bg-light rounded-circle p-3 me-3 d-flex align-items-center justify-content-center text-secondary" style="width: 50px; height: 50px;">
                                <i class="bi '.$c[2].' fs-4"></i>
                            </div>
                            <div>
                                <p class="small text-uppercase text-muted mb-1 fw-semibold" style="letter-spacing: 0.5px; font-size: 0.75rem;">'.$c[0].'</p>
                                <h4 class="fw-bold text-dark mb-0">'.$valor_formateado.'</h4>
                            </div>
                        </div>
                    </div>
                  </div>';
        }
        ?>
    </div>

    <!-- Gráfico Comercial Modificable -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                        <h5 class="fw-bold text-dark mb-0">
                            <i class="bi bi-bezier2 me-2" style="color: #6f42c1;"></i>Flujo Comercial
                        </h5>
                        <!-- Selectores de período -->
                        <div class="btn-group p-1 bg-light rounded-pill" role="group" aria-label="Período comercial">
                            <button type="button" class="btn btn-sm btn-purple-toggle rounded-pill px-3 active" onclick="cambiarPeriodo('semanal', this)">Semanal</button>
                            <button type="button" class="btn btn-sm btn-purple-toggle rounded-pill px-3" onclick="cambiarPeriodo('mensual', this)">Mensual</button>
                            <button type="button" class="btn btn-sm btn-purple-toggle rounded-pill px-3" onclick="cambiarPeriodo('anual', this)">Anual</button>
                        </div>
                    </div>
                    <div style="height: 240px; position: relative;">
                        <canvas id="graficoVentas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secciones Inferiores (Tablas) -->
    <div class="row g-4 mb-4">
        <!-- Stock Crítico -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Stock Crítico de Calzado</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 small fw-bold text-muted">Zapato</th>
                                <th class="small fw-bold text-muted">Talla</th>
                                <th class="text-center small fw-bold text-muted">Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($stock_bajo && mysqli_num_rows($stock_bajo) > 0): ?>
                                <?php while($p = mysqli_fetch_assoc($stock_bajo)): ?>
                                <tr>
                                    <td class="ps-3 fw-bold small text-dark"><?php echo htmlspecialchars($p['nombre']); ?></td>
                                    <td><span class="badge bg-light text-dark border">T: <?php echo htmlspecialchars($p['talla']); ?></span></td>
                                    <td class="text-center">
                                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.75rem;">
                                            <?php echo $p['stock']; ?> und.
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted small py-4">Inventario óptimo.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Top 5 Modelos -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 bg-white">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold text-dark mb-0"><i class="bi bi-star text-warning me-2"></i>Top 5 Modelos Más Vendidos</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3 small fw-bold text-muted">Silueta / Estilo</th>
                                <th class="small fw-bold text-muted">Marca</th>
                                <th class="text-center small fw-bold text-muted">Salidas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($top_vendidos && mysqli_num_rows($top_vendidos) > 0): ?>
                                <?php while($tv = mysqli_fetch_assoc($top_vendidos)): ?>
                                <tr>
                                    <td class="ps-3 fw-medium text-dark small"><?php echo htmlspecialchars($tv['nombre']); ?></td>
                                    <td class="text-muted small"><?php echo htmlspecialchars($tv['marca']); ?></td>
                                    <td class="text-center fw-bold text-dark small"><?php echo $tv['total_vendido']; ?> pares</td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="3" class="text-center text-muted small py-4">Sin registros comerciales acumulados.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos personalizados para los selectores -->
<style>
.btn-purple-toggle {
    color: #6c757d;
    border: none;
    font-weight: 500;
    transition: all 0.2s ease;
}
.btn-purple-toggle:hover {
    color: #6f42c1;
    background-color: rgba(111, 66, 193, 0.08);
}
.btn-purple-toggle.active, .btn-purple-toggle:active {
    background-color: #6f42c1 !important;
    color: #ffffff !important;
    font-weight: bold;
    box-shadow: 0 2px 4px rgba(111, 66, 193, 0.2);
}
</style>

<script>
// Inyectamos de forma segura los arreglos de PHP a constantes de JavaScript
$datosGrafico = {
    semanal: {
        labels: <?php echo json_encode($datos_semanal['labels']); ?>,
        data: <?php echo json_encode($datos_semanal['data']); ?>
    },
    mensual: {
        labels: <?php echo json_encode($datos_mensual['labels']); ?>,
        data: <?php echo json_encode($datos_mensual['data']); ?>
    },
    anual: {
        labels: <?php echo json_encode($datos_anual['labels']); ?>,
        data: <?php echo json_encode($datos_anual['data']); ?>
    }
};

const ctx = document.getElementById('graficoVentas').getContext('2d');

// Inicialización por defecto con los datos semanales
const miGrafico = new Chart(ctx, {
    type: 'line',
    data: {
        labels: $datosGrafico.semanal.labels,
        datasets: [{
            label: 'Monto de Ventas',
            data: $datosGrafico.semanal.data, 
            borderColor: '#6f42c1', 
            backgroundColor: 'rgba(111, 66, 193, 0.05)',
            tension: 0.35,
            fill: true,
            borderWidth: 2.5,
            pointRadius: 3,
            pointHoverRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { 
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) { label += ': '; }
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('es-CO', { style: 'currency', currency: 'COP', maximumFractionDigits: 0 }).format(context.parsed.y);
                        }
                        return label;
                    }
                }
            }
        },
        scales: { 
            y: { 
                grid: { color: '#f1f1f1' }, 
                ticks: { 
                    font: { size: 10 },
                    callback: function(value) {
                        return '$' + value.toLocaleString('es-CO');
                    }
                } 
            },
            x: { grid: { display: false }, ticks: { font: { size: 10 } } }
        }
    }
});

// Función para alternar el set de datos del gráfico
function cambiarPeriodo(periodo, elemento) {
    // Cambiar estado activo de los botones
    document.querySelectorAll('.btn-purple-toggle').forEach(btn => btn.classList.remove('active'));
    elemento.classList.add('active');
    
    // Mutar los datos internos de la instancia de Chart.js
    miGrafico.data.labels = $datosGrafico[periodo].labels;
    miGrafico.data.datasets[0].data = $datosGrafico[periodo].data;
    
    // Renderizar la animación de transición con los nuevos valores
    miGrafico.update();
}
</script>

<?php include("includes/footer.php"); ?>