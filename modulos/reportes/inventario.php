<?php
include("../../conexion.php");
include("../../includes/header.php");

$productos = mysqli_query($conexion,"SELECT * FROM productos ORDER BY nombre ASC");
?>

<div class="container-fluid">

<!-- TITULO -->
<div class="d-flex justify-content-between align-items-center mb-2">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Inventario General</h2>
        <p class="text-muted small">Listado completo de productos disponibles.</p>
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

<a href="ventas_dia.php" class="btn btn-outline-success">
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


<!-- TABLA INVENTARIO -->

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="table-responsive">

<table class="table align-middle mb-0 table-hover">

<thead class="table-light">

<tr>
<th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Producto</th>
<th class="text-uppercase small fw-bold text-muted">Marca</th>
<th class="text-uppercase small fw-bold text-muted">Talla</th>
<th class="text-uppercase small fw-bold text-muted">Precio</th>
<th class="text-uppercase small fw-bold text-muted">Stock</th>
</tr>

</thead>

<tbody>

<?php while($p=mysqli_fetch_array($productos)){ ?>

<tr>

<td class="ps-4">

<div class="d-flex align-items-center">

<div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
style="width:35px;height:35px;">
<i class="bi bi-box"></i>
</div>

<span class="fw-bold"><?php echo $p['nombre']; ?></span>

</div>

</td>


<td><?php echo $p['marca']; ?></td>


<td>

<span class="badge bg-secondary">
<?php echo $p['talla']; ?>
</span>

</td>


<td class="fw-bold text-success">

$<?php echo number_format($p['precio'],0,",","."); ?>

</td>


<td>

<?php if($p['stock'] <= 5){ ?>

<span class="badge bg-danger">
<?php echo $p['stock']; ?> Bajo
</span>

<?php } else { ?>

<span class="badge bg-success">
<?php echo $p['stock']; ?>
</span>

<?php } ?>

</td>


</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<?php include("../../includes/footer.php"); ?>