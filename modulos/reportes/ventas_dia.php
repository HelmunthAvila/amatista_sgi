<?php
include("../../conexion.php");
include("../../includes/header.php");

$hoy = date("Y-m-d");

$ventas = mysqli_query($conexion,"
SELECT ventas.*, clientes.nombre 
FROM ventas
JOIN clientes ON ventas.id_cliente = clientes.id
WHERE DATE(ventas.fecha) = '$hoy'
ORDER BY ventas.fecha DESC
");
?>

<div class="container-fluid">

<!-- TITULO -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Ventas del Día</h2>
        <p class="text-muted small">Listado de ventas realizadas hoy.</p>
    </div>
</div>


<!-- MENU REPORTES -->
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">

<div class="btn-group shadow-sm">

<a href="inventario.php" class="btn btn-outline-primary">
<i class="bi bi-box-seam"></i> Inventario
</a>

<a href="stock_bajo.php" class="btn btn-outline-warning">
<i class="bi bi-exclamation-triangle"></i> Stock Bajo
</a>

<a href="ventas_dia.php" class="btn btn-success">
<i class="bi bi-calendar-day"></i> Ventas Día
</a>

<a href="ventas_mes.php" class="btn btn-outline-info">
<i class="bi bi-calendar-month"></i> Ventas Mes
</a>

<a href="exportar_excel.php" class="btn btn-outline-success">
<i class="bi bi-file-earmark-excel"></i> Exportar Excel
</a>

</div>


<form method="GET" action="ventas_filtro.php" class="d-flex gap-2">

<input type="date" name="fecha_inicio" class="form-control">

<input type="date" name="fecha_fin" class="form-control">

<button class="btn btn-dark">
<i class="bi bi-search"></i> Filtrar
</button>

</form>

</div>


<!-- TABLA -->

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

<?php while($v=mysqli_fetch_array($ventas)){ ?>

<tr>

<td class="ps-4 fw-bold"><?php echo $v['id']; ?></td>

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

<?php include("../../includes/footer.php"); ?>