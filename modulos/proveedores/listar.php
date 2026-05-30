<?php

// 1. Incluir el archivo de conexión a la base de datos
include("../../conexion.php");

// 2. Incluir el encabezado del sistema (menú, estilos, estructura)
include("../../includes/header.php");

// Captura el filtro de búsqueda enviado por la URL (GET)
$busqueda = $_GET['busqueda'] ?? '';

// Define la consulta base para obtener los proveedores
$query = "SELECT * FROM proveedores WHERE 1=1";

// Aplica el filtro si el usuario realiza una búsqueda
if (!empty($busqueda)) {
    $busqueda_escapada = mysqli_real_escape_string($conexion, $busqueda);
    $query .= " AND (empresa LIKE '%$busqueda_escapada%' OR nombre LIKE '%$busqueda_escapada%')";
}

// Ordena todos los proveedores alfabéticamente por nombre de empresa
$query .= " ORDER BY empresa ASC";

// Ejecuta la consulta
$proveedores = mysqli_query($conexion, $query);

if (!$proveedores) {
    die("Error en la consulta de proveedores: " . mysqli_error($conexion));
}
?>

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Gestión de Proveedores</h2>
            <p class="text-muted small">Directorio de fábricas y contactos de suministros de calzado.</p>
        </div>

        <a href="agregar.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Registrar Proveedor
        </a>
    </div>

    <div class="card shadow-sm border-0 mb-4 rounded-4">
        <div class="card-body p-3">
            <form method="GET" action="">
                <div class="row g-3 align-items-end">
                    
                    <div class="col-md-9">
                        <label class="form-label small fw-bold text-muted text-uppercase">Buscar Fábrica o Contacto</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted"><i class="bi bi-search"></i></span>
                            <input type="text" name="busqueda" class="form-control bg-light border-0" 
                                   placeholder="Ej. Vizzano, Carlos Ruiz, Aldo Group..." 
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
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Empresa / Fábrica</th>
                        <th class="text-uppercase small fw-bold text-muted">Contacto Principal</th>
                        <th class="text-uppercase small fw-bold text-muted">Teléfono</th>
                        <th class="text-center text-uppercase small fw-bold text-muted">Acciones</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($proveedores) > 0) { ?>
                        <?php while($p = mysqli_fetch_array($proveedores)){ ?>

                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-light text-primary rounded-3 d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-building"></i>
                                    </div>
                                    <div>
                                        <span class="fw-bold text-dark d-block"><?php echo $p['empresa']; ?></span>
                                        <span class="text-muted small">Proveedor Activo</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <span class="text-dark fw-medium"><?php echo $p['nombre']; ?></span>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <a href="tel:<?php echo $p['telefono']; ?>" class="text-decoration-none text-muted mb-1 small">
                                        <i class="bi bi-telephone-fill text-primary me-2"></i>
                                        <?php echo $p['telefono']; ?>
                                    </a>
                                    <a href="https://wa.me/57<?php echo str_replace(' ', '', $p['telefono']); ?>" target="_blank" class="btn btn-sm btn-outline-success border-0 p-0 text-start" style="font-size: 0.75rem;">
                                        <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                    </a>
                                </div>
                            </td>

                            <td class="text-center">
                                <div class="btn-group shadow-sm rounded-3">
                                    <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white border-end" title="Editar">
                                        <i class="bi bi-pencil-square text-primary"></i>
                                    </a>
                                    <a href="eliminar.php?id=<?php echo $p['id']; ?>" 
                                       class="btn btn-sm btn-white"
                                       onclick="return confirm('¿Seguro que desea eliminar este proveedor?')" 
                                       title="Eliminar">
                                        <i class="bi bi-trash3 text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-building-x fs-2 d-block mb-2 text-secondary"></i>
                                No se encontraron proveedores que coincidan con la búsqueda.
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// 4. Incluir el pie de página del sistema
include("../../includes/footer.php"); 
?>