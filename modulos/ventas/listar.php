<?php
include("../../conexion.php");
include("../../includes/header.php");

// Capturar filtros
$fecha_inicio = $_GET['fecha_inicio'] ?? '';
$fecha_fin = $_GET['fecha_fin'] ?? '';

// Consulta base
$query = "SELECT v.id, v.fecha, v.total, c.nombre as cliente 
          FROM ventas v
          INNER JOIN clientes c ON v.id_cliente = c.id
          WHERE 1=1";

// Aplicar filtros
if(!empty($fecha_inicio)){
    $query .= " AND DATE(v.fecha) >= '$fecha_inicio'";
}

if(!empty($fecha_fin)){
    $query .= " AND DATE(v.fecha) <= '$fecha_fin'";
}

$query .= " ORDER BY v.fecha DESC";

$ventas = mysqli_query($conexion,$query);

if(!$ventas){
    die("Error en consulta: ".mysqli_error($conexion));
}
?>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
<div>
<h2 class="fw-bold mb-0 text-dark">Historial de Ventas</h2>
<p class="text-muted small">Consulta y reimprime facturas de Amatista SGI.</p>
</div>

<a href="pos.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
<i class="bi bi-cart-plus me-2"></i>Nueva Venta
</a>
</div>

<!-- FILTROS -->
<div class="card shadow-sm border-0 mb-4">
<div class="card-body">

<form method="GET">

<div class="row">

<div class="col-md-4">
<label class="form-label">Fecha Inicio</label>
<input type="date" name="fecha_inicio" class="form-control"
value="<?php echo $fecha_inicio; ?>">
</div>

<div class="col-md-4">
<label class="form-label">Fecha Fin</label>
<input type="date" name="fecha_fin" class="form-control"
value="<?php echo $fecha_fin; ?>">
</div>

<div class="col-md-4 d-flex align-items-end gap-2">

<button class="btn btn-primary">
<i class="bi bi-search"></i> Filtrar
</button>

<a href="listar.php" class="btn btn-secondary">
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
<th class="ps-4">ID</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Total</th>
<th class="text-center">Acciones</th>
</tr>
</thead>

<tbody>

<?php if(mysqli_num_rows($ventas)>0){ ?>

<?php while($v = mysqli_fetch_assoc($ventas)){ ?>

<tr>

<td class="ps-4">
<span class="badge bg-primary">
#<?php echo $v['id']; ?>
</span>
</td>

<td>
<?php echo date("d/m/Y H:i",strtotime($v['fecha'])); ?>
</td>

<td>
<?php echo $v['cliente']; ?>
</td>

<td class="fw-bold text-success">
$<?php echo number_format($v['total']); ?>
</td>

<td class="text-center">

<div class="btn-group">

<a href="ver_detalle.php?id=<?php echo $v['id']; ?>"
class="btn btn-sm btn-light border">
<i class="bi bi-eye"></i>
</a>

<a href="ticket.php?id=<?php echo $v['id']; ?>"
target="_blank"
class="btn btn-sm btn-light border">
<i class="bi bi-printer"></i>
</a>

<a href="eliminar.php?id=<?php echo $v['id']; ?>"
class="btn btn-sm btn-light text-danger"
onclick="return confirm('¿Anular venta?')">
<i class="bi bi-trash"></i>
</a>

</div>

</td>

</tr>

<?php } ?>

<?php } else { ?>

<tr>
<td colspan="5" class="text-center py-4">
No hay ventas en ese rango de fechas.
</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include("../../includes/footer.php"); ?>