<?php
include("conexion.php");
include("includes/header.php");

/* VENTAS DEL DIA */
$ventas_dia = mysqli_query($conexion,"
SELECT SUM(total) as total 
FROM ventas 
WHERE DATE(fecha)=CURDATE()
");
$ventas_dia = mysqli_fetch_assoc($ventas_dia);

/* VENTAS DEL MES */
$ventas_mes = mysqli_query($conexion,"
SELECT SUM(total) as total 
FROM ventas 
WHERE MONTH(fecha)=MONTH(CURDATE())
AND YEAR(fecha)=YEAR(CURDATE())
");
$ventas_mes = mysqli_fetch_assoc($ventas_mes);

/* TOTAL PRODUCTOS */
$productos = mysqli_query($conexion,"SELECT COUNT(*) as total FROM productos");
$productos = mysqli_fetch_assoc($productos);

/* STOCK BAJO */
$stock_bajo_total = mysqli_query($conexion,"
SELECT COUNT(*) as total 
FROM productos 
WHERE stock<=5
");
$stock_bajo_total = mysqli_fetch_assoc($stock_bajo_total);

$stock_bajo = mysqli_query($conexion,"
SELECT * 
FROM productos 
WHERE stock<=5
ORDER BY stock ASC
");
?>

<div class="container-fluid">

<!-- TITULO -->
<div class="d-flex justify-content-between align-items-center mb-4">

<h1 class="fw-bold text-dark">
Dashboard AMATISTA SGI
</h1>

<div class="bg-white shadow-sm px-4 py-2 rounded-pill border">
<span class="text-muted small">Bienvenido</span>
<strong><?php echo $_SESSION['usuario'] ?? 'admin'; ?></strong>
<i class="bi bi-person-circle ms-2 text-primary"></i>
</div>

</div>


<!-- TARJETAS -->

<div class="row g-4 mb-4">

<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4 text-white"
style="background:#2563eb">

<div class="card-body text-center">

<i class="bi bi-cash-stack fs-1"></i>

<p class="small text-uppercase mt-2">Ventas Hoy</p>

<h3 class="fw-bold">
$<?php echo number_format($ventas_dia['total'] ?? 0,0,",","."); ?>
</h3>

</div>
</div>
</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4 text-white"
style="background:#059669">

<div class="card-body text-center">

<i class="bi bi-graph-up fs-1"></i>

<p class="small text-uppercase mt-2">Ventas del Mes</p>

<h3 class="fw-bold">
$<?php echo number_format($ventas_mes['total'] ?? 0,0,",","."); ?>
</h3>

</div>
</div>
</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4 text-white"
style="background:#7c3aed">

<div class="card-body text-center">

<i class="bi bi-box-seam fs-1"></i>

<p class="small text-uppercase mt-2">Productos</p>

<h3 class="fw-bold">
<?php echo $productos['total']; ?>
</h3>

</div>
</div>
</div>



<div class="col-md-3">

<div class="card border-0 shadow-sm rounded-4 text-white"
style="background:#dc2626">

<div class="card-body text-center">

<i class="bi bi-exclamation-triangle fs-1"></i>

<p class="small text-uppercase mt-2">Stock Bajo</p>

<h3 class="fw-bold">
<?php echo $stock_bajo_total['total']; ?>
</h3>

</div>
</div>
</div>

</div>


<!-- ACCESOS RAPIDOS -->

<div class="card border-0 shadow-sm rounded-4 mb-4">

<div class="card-body">

<h5 class="fw-bold mb-3">
Accesos rápidos
</h5>

<div class="row g-3">

<div class="col-md-3">
<a href="modulos/ventas/pos.php"
class="btn btn-primary w-100 py-3">
<i class="bi bi-cart fs-4"></i><br>
Nueva Venta
</a>
</div>

<div class="col-md-3">
<a href="modulos/productos/listar.php"
class="btn btn-success w-100 py-3">
<i class="bi bi-box-seam fs-4"></i><br>
Productos
</a>
</div>

<div class="col-md-3">
<a href="modulos/clientes/listar.php"
class="btn btn-info w-100 py-3 text-white">
<i class="bi bi-people fs-4"></i><br>
Clientes
</a>
</div>

<div class="col-md-3">
<a href="modulos/reportes/inventario.php"
class="btn btn-dark w-100 py-3">
<i class="bi bi-bar-chart fs-4"></i><br>
Reportes
</a>
</div>

</div>

</div>

</div>


<!-- STOCK BAJO -->

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="card-header bg-white border-0">

<h5 class="fw-bold text-danger mb-0">

<i class="bi bi-exclamation-triangle-fill me-2"></i>

Productos con Stock Bajo

</h5>

</div>

<div class="table-responsive">

<table class="table table-hover align-middle mb-0">

<thead class="table-light">

<tr>

<th class="ps-4">Producto</th>
<th>Marca</th>
<th>Talla</th>
<th class="text-center">Stock</th>

</tr>

</thead>

<tbody>

<?php 
$hay=false;

while($p=mysqli_fetch_array($stock_bajo)){
$hay=true;
?>

<tr>

<td class="ps-4 fw-bold">
<?php echo $p['nombre']; ?>
</td>

<td>
<?php echo $p['marca']; ?>
</td>

<td>
<?php echo $p['talla']; ?>
</td>

<td class="text-center">

<span class="badge bg-danger px-3 py-2">
<?php echo $p['stock']; ?>
</span>

</td>

</tr>

<?php } ?>

<?php
if(!$hay){
echo "<tr>
<td colspan='4' class='text-center py-5 text-muted'>
Inventario en niveles óptimos
</td>
</tr>";
}
?>

</tbody>

</table>

</div>

</div>

</div>

<?php include("includes/footer.php"); ?>