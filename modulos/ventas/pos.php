<?php
// Inicia la sesión para utilizar el carrito almacenado en variables de sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include("../../conexion.php");

// Incluye el encabezado del sistema (menú, estilos y navegación)
include("../../includes/header.php");

// Consulta los productos disponibles con stock mayor a cero ordenados por nombre
$productos = mysqli_query($conexion, "SELECT * FROM productos WHERE stock > 0 ORDER BY nombre ASC");

// Consulta todos los clientes registrados ordenados por nombre
$clientes = mysqli_query($conexion, "SELECT * FROM clientes ORDER BY nombre ASC");

// Inicializa el carrito en la sesión si aún no existe
if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}
?>

<div class="container-fluid">

<!-- Encabezado del módulo POS -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0 text-dark">Terminal Punto de Venta (POS)</h2>
        <p class="text-muted small">Registra ventas y gestiona el carrito.</p>
    </div>

    <!-- Botón para acceder al historial de ventas -->
    <a href="listar.php" class="btn btn-outline-primary rounded-pill px-4 shadow-sm">
        <i class="bi bi-receipt me-2"></i>Ver Historial de Ventas
    </a>
</div>

<div class="row">

<!-- MÓDULO PARA AGREGAR PRODUCTOS AL CARRITO -->
<div class="col-md-4">

<div class="card border-0 shadow-sm rounded-4 mb-4">
<div class="card-body p-4">

<h5 class="fw-bold mb-3 text-primary">
<i class="bi bi-plus-circle me-2"></i>Agregar al Carrito
</h5>

<!-- Formulario que envía el producto seleccionado al archivo agregar_carrito.php -->
<form action="agregar_carrito.php" method="POST">

<div class="mb-3">
<label class="form-label small fw-bold text-muted text-uppercase">Producto</label>

<!-- Lista desplegable con productos disponibles -->
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

<!-- Campo para indicar la cantidad del producto -->
<input type="number" name="cantidad" class="form-control bg-light border-0" value="1" min="1" required>
</div>

<!-- Botón para agregar el producto al carrito -->
<button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
<i class="bi bi-cart-plus me-2"></i>Agregar Producto
</button>

</form>

</div>
</div>

</div>

<!-- MÓDULO DEL CARRITO DE COMPRAS -->
<div class="col-md-8">

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">

<div class="table-responsive">

<!-- Tabla que muestra los productos agregados al carrito -->
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
// Variable para acumular el total de la venta
$total = 0;

// Verifica si el carrito está vacío
if(empty($_SESSION['carrito'])){
?>

<tr>
<td colspan="5" class="text-center py-5 text-muted">
El carrito está vacío
</td>
</tr>

<?php
}else{

// Recorre todos los productos almacenados en el carrito
foreach($_SESSION['carrito'] as $index => $item){

// Calcula el subtotal del producto
$subtotal = $item['precio'] * $item['cantidad'];

// Acumula el subtotal en el total de la venta
$total += $subtotal;
?>

<tr>

<!-- Nombre del producto -->
<td class="ps-4 fw-bold text-dark">
<?php echo $item['nombre']; ?>
</td>

<!-- Precio unitario -->
<td>
$<?php echo number_format($item['precio']); ?>
</td>

<!-- Cantidad del producto -->
<td>
<span class="badge bg-light text-dark border">
<?php echo $item['cantidad']; ?>
</span>
</td>

<!-- Subtotal del producto -->
<td class="fw-bold text-primary">
$<?php echo number_format($subtotal); ?>
</td>

<!-- Botón para eliminar el producto del carrito -->
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

<!-- MÓDULO DE TOTAL DE VENTA Y ASIGNACIÓN DE CLIENTE -->

<div class="card-footer bg-white p-4 border-top">

<!-- Formulario que guarda la venta en la base de datos -->
<form action="guardar_venta.php" method="POST">

<div class="row align-items-end">

<div class="col-md-7">

<label class="form-label small fw-bold text-muted text-uppercase">
Asignar Cliente
</label>

<!-- Lista desplegable para seleccionar el cliente de la venta -->
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

<!-- Muestra el total acumulado de la venta -->
<p class="mb-1 text-muted small text-uppercase fw-bold">
Total Venta
</p>

<h2 class="fw-bold text-dark mb-3">
$<?php echo number_format($total); ?>
</h2>

<!-- Botón para completar la venta y guardar los datos -->
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

<!-- Incluye el pie de página del sistema -->
<?php include("../../includes/footer.php"); ?>