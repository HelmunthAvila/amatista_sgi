<?php

// 1. Conexión a la base de datos
include("../../conexion.php");

// 2. Cargar encabezado del sistema (menú, estilos, navbar)
include("../../includes/header.php");

// 3. Obtener mes y año actual del servidor
$mes = date("m");
$anio = date("Y");

/*--------------------------------------------------
CONSULTA: VENTAS REALIZADAS EN EL MES ACTUAL
--------------------------------------------------*/

$ventas = mysqli_query($conexion,"
SELECT ventas.*, clientes.nombre 
FROM ventas
JOIN clientes ON ventas.id_cliente = clientes.id
WHERE MONTH(ventas.fecha)='$mes' 
AND YEAR(ventas.fecha)='$anio'
ORDER BY ventas.fecha DESC
");


/*--------------------------------------------------
CONSULTA: TOTAL DE DINERO VENDIDO EN EL MES
--------------------------------------------------*/

$total_mes_query = mysqli_query($conexion,"
SELECT SUM(total) as total_mes 
FROM ventas 
WHERE MONTH(fecha)='$mes' 
AND YEAR(fecha)='$anio'
");

// Obtener el resultado de la consulta
$total_mes = mysqli_fetch_assoc($total_mes_query);

// Si no hay ventas, se asigna 0
$total_mes = $total_mes['total_mes'] ?? 0;

?>

<div class="container-fluid">

<!-- TITULO DEL REPORTE -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>

        <!-- Nombre del reporte -->
        <h2 class="fw-bold mb-0 text-dark">Ventas del Mes</h2>

        <!-- Descripción -->
        <p class="text-muted small">
        Listado de ventas realizadas durante el mes actual.
        </p>

    </div>
</div>


<!-- MENU DE REPORTES DEL SISTEMA -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

<div class="btn-group shadow-sm">

<!-- Inventario general -->
<a href="inventario.php" class="btn btn-outline-primary">
<i class="bi bi-box-seam"></i> Inventario
</a>

<!-- Productos con stock bajo -->
<a href="stock_bajo.php" class="btn btn-outline-warning">
<i class="bi bi-exclamation-triangle"></i> Stock Bajo
</a>

<!-- Reporte ventas del día -->
<a href="ventas_dia.php" class="btn btn-outline-success">
<i class="bi bi-calendar-day"></i> Ventas Día
</a>

<!-- Reporte actual: ventas del mes -->
<a href="ventas_mes.php" class="btn btn-info text-white">
<i class="bi bi-calendar-month"></i> Ventas Mes
</a>

<!-- Exportar inventario a Excel -->
<a href="exportar_excel.php" class="btn btn-outline-success">
<i class="bi bi-file-earmark-excel"></i> Exportar Excel
</a>

</div>


<!-- FILTRO DE VENTAS POR RANGO DE FECHAS -->
<form method="GET" action="ventas_filtro.php" class="d-flex gap-2">

<input type="date" name="fecha_inicio" class="form-control">

<input type="date" name="fecha_fin" class="form-control">

<button class="btn btn-dark">
<i class="bi bi-search"></i> Filtrar
</button>

</form>

</div>


<!-- TARJETA CON EL TOTAL VENDIDO EN EL MES -->

<div class="row mb-3">

<div class="col-md-4">

<div class="card border-0 shadow-sm rounded-4">
<div class="card-body">

<h6 class="text-muted">Total vendido este mes</h6>

<h3 class="fw-bold text-success">
$<?php echo number_format($total_mes,0,",","."); ?>
</h3>

</div>
</div>

</div>

</div>


<!-- TABLA DE VENTAS DEL MES -->

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="table-responsive">

<table class="table align-middle mb-0 table-hover">

<thead class="table-light">

<tr>
<th class="ps-4 py-3 text-uppercase small fw-bold text-muted">ID</th>
<th class="text-uppercase small fw-bold text-muted">Cliente</th>
<th class="text-uppercase small fw-bold text-muted">Fecha</th>
<th class="text-uppercase small fw-bold text-muted">Total</th>
<th class="text-uppercase small fw-bold text-muted text-center">Acciones</th>
</tr>

</thead>

<tbody>

<!-- Recorrer todas las ventas del mes -->
<?php while($v=mysqli_fetch_array($ventas)){ ?>

<tr>

<td class="ps-4 fw-bold">
<?php echo $v['id']; ?>
</td>

<td>
<i class="bi bi-person text-primary me-2"></i>
<?php echo $v['nombre']; ?>
</td>

<td>
<i class="bi bi-clock text-muted me-2"></i>
<?php echo $v['fecha']; ?>
</td>

<td class="fw-bold text-success">
$<?php echo number_format($v['total'],0,",","."); ?>
</td>

<td class="text-center">

<!-- Botón para ver el detalle de la venta -->
<a href="../ventas/ver_detalle.php?id=<?php echo $v['id']; ?>" 
class="btn btn-sm btn-outline-primary">

<i class="bi bi-eye"></i> Ver

</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php

// 4. Cargar pie de página del sistema
include("../../includes/footer.php");

?>