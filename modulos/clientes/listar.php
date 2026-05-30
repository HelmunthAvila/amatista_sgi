<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Captura el filtro de búsqueda enviado por la URL (GET)
$busqueda = $_GET['busqueda'] ?? '';

// Define la consulta base para obtener los clientes
$query = "SELECT * FROM clientes WHERE 1=1";

// Aplica el filtro si el usuario escribe en el buscador
if (!empty($busqueda)) {
    $busqueda_escapada = mysqli_real_escape_string($conexion, $busqueda);
    $query .= " AND (nombre LIKE '%$busqueda_escapada%' 
                     OR telefono LIKE '%$busqueda_escapada%' 
                     OR email LIKE '%$busqueda_escapada%')";
}

// Ordena los clientes alfabéticamente por nombre
$query .= " ORDER BY nombre ASC";

// Ejecuta la consulta
$clientes = mysqli_query($conexion, $query);

if (!$clientes) {
    die("Error en la consulta de clientes: " . mysqli_error($conexion));
}
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Directorio de Clientes</h2>
            <p class="text-muted small">Administra la información de contacto de tus compradores.</p>
        </div>

        <a href="agregar.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Registrar Cliente
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="listar.php">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-9">
                        <label class="form-label small fw-bold text-muted text-uppercase">Buscar Cliente</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busqueda" class="form-control bg-light border-0" 
                                   placeholder="Buscar por nombre, teléfono o correo electrónico..." 
                                   value="<?php echo htmlspecialchars($busqueda); ?>">
                        </div>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">
                            Filtrar
                        </button>
                        <a href="listar.php" class="btn btn-light border w-100 rounded-pill">
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
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Nombre</th>
                        <th class="text-uppercase small fw-bold text-muted">Contacto Directo</th>
                        <th class="text-uppercase small fw-bold text-muted">Email</th>
                        <th class="text-center text-uppercase small fw-bold text-muted">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($clientes) > 0) { ?>
                        <?php while($c = mysqli_fetch_array($clientes)){ 
                            // Limpia el número telefónico eliminando espacios, guiones o puntos para usarlo en enlaces
                            $tel_limpio = str_replace([' ', '-', '.'], '', $c['telefono']);
                        ?>

                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                    <span class="fw-bold text-dark"><?php echo $c['nombre']; ?></span>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <a href="tel:<?php echo $tel_limpio; ?>" class="text-decoration-none text-dark mb-1">
                                        <i class="bi bi-telephone-fill text-primary me-2 small"></i><?php echo $c['telefono']; ?>
                                    </a>
                                    <a href="https://wa.me/57<?php echo $tel_limpio; ?>" target="_blank" class="text-success text-decoration-none small fw-bold">
                                        <i class="bi bi-whatsapp me-1"></i> Enviar Mensaje
                                    </a>
                                </div>
                            </td>

                            <td>
                                <a href="mailto:<?php echo $c['email']; ?>" class="text-muted text-decoration-none">
                                    <i class="bi bi-envelope me-2"></i><?php echo $c['email']; ?>
                                </a>
                            </td>

                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-3">
                                    <a href="editar.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-white border-end" title="Editar">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <a href="eliminar.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-white" 
                                       onclick="return confirm('¿Está seguro de eliminar a este cliente?')" title="Eliminar">
                                        <i class="bi bi-trash text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-person-x fs-2 d-block mb-2 text-secondary"></i>
                                No se encontraron clientes que coincidan con la búsqueda.
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// Incluye el pie de página del sistema
include("../../includes/footer.php"); 
?>