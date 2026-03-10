<?php
session_start();
include("../../conexion.php");
include("../../includes/header.php");

// Productos con stock
$productos = mysqli_query($conexion, "SELECT * FROM productos WHERE stock > 0 ORDER BY nombre ASC");

// Clientes
$clientes = mysqli_query($conexion, "SELECT * FROM clientes ORDER BY nombre ASC");

// Inicializar carrito
if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}
?>

<div class="container-fluid">

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Terminal Punto de Venta (POS)</h2>
        <p class="text-muted small">Registra ventas y gestiona el carrito.</p>
    </div>

    <a href="listar.php" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-receipt me-2"></i>Ver Historial de Ventas
    </a>
</div>

<div class="row">

<!-- AGREGAR PRODUCTO -->
<div class="col-md-4">

<div class="card border-0 shadow-sm rounded-4 mb-4">
<div class="card-body p-4">

<h5 class="fw-bold mb-3 text-primary">
<i class="bi bi-plus-circle me-2"></i>Agregar al Carrito
</h5>

<form action="agregar_carrito.php" method="POST">

<div class="mb-3">
<label class="form-label small fw-bold text-muted text-uppercase">Producto</label>

<select name="producto_id" class="form-select bg-light border-0" required>

<option value="">Seleccionar producto...</option>

<?php while($p = mysqli_fetch_array($productos)){ ?>

<option value="<?php echo $p['id']; ?>">
<?php echo $p['nombre']." (Stock: ".$p['stock'].") - $".number_format($p['precio']); ?>
</option>

<?php } ?>

</select>
</div>

<div class="mb-3">
<label class="form-label small fw-bold text-muted text-uppercase">Cantidad</label>

<input type="number" name="cantidad" class="form-control bg-light border-0" value="1" min="1" required>
</div>

<button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
<i class="bi bi-cart-plus me-2"></i>Agregar Producto
</button>

</form>

</div>
</div>

</div>

<!-- CARRITO -->
<div class="col-md-8">

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="table-responsive">

<table class="table align-middle mb-0">

<thead class="table-light">
<tr>
<th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Producto</th>
<th class="text-uppercase small fw-bold text-muted">Precio</th>
<th class="text-uppercase small fw-bold text-muted">Cantidad</th>
<th class="text-uppercase small fw-bold text-muted">Subtotal</th>
<th class="text-center text-uppercase small fw-bold text-muted">Quitar</th>
</tr>
</thead>

<tbody>

<?php
$total = 0;

if(empty($_SESSION['carrito'])){
?>

<tr>
<td colspan="5" class="text-center py-5 text-muted">
El carrito está vacío
</td>
</tr>

<?php
}else{

foreach($_SESSION['carrito'] as $index => $item){

$subtotal = $item['precio'] * $item['cantidad'];
$total += $subtotal;
?>

<tr>

<td class="ps-4 fw-bold text-dark">
<?php echo $item['nombre']; ?>
</td>

<td>
$<?php echo number_format($item['precio']); ?>
</td>

<td>
<span class="badge bg-light text-dark border">
<?php echo $item['cantidad']; ?>
</span>
</td>

<td class="fw-bold text-primary">
$<?php echo number_format($subtotal); ?>
</td>

<td class="text-center">

<a href="eliminar_carrito.php?id=<?php echo $index; ?>"
class="btn btn-sm btn-light text-danger rounded-circle">

<i class="bi bi-trash3"></i>

</a>

</td>

</tr>

<?php
}
}
?>

</tbody>

</table>

</div>

<!-- TOTAL Y CLIENTE -->

<div class="card-footer bg-white p-4 border-top">

<form action="guardar_venta.php" method="POST">

<div class="row align-items-end">

<div class="col-md-7">

<label class="form-label small fw-bold text-muted text-uppercase">
Asignar Cliente
</label>

<select name="id_cliente" class="form-select bg-light border-0" required>

<option value="">Seleccionar cliente...</option>

<?php while($c = mysqli_fetch_array($clientes)){ ?>

<option value="<?php echo $c['id']; ?>">
<?php echo $c['nombre']; ?>
</option>

<?php } ?>

</select>

</div>

<div class="col-md-5 text-end">

<p class="mb-1 text-muted small text-uppercase fw-bold">
Total Venta
</p>

<h2 class="fw-bold text-dark mb-3">
$<?php echo number_format($total); ?>
</h2>

<button type="submit"
class="btn btn-success w-100 rounded-pill shadow-sm py-2"
<?php echo (empty($_SESSION['carrito'])) ? 'disabled' : ''; ?>>

<i class="bi bi-bag-check-fill me-2"></i>
Completar Venta

</button>

</div>

</div>

</form>

</div>

</div>

</div>

</div>

</div>

<?php include("../../includes/footer.php"); ?>