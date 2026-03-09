<?php
// 1. Inclusión de base y diseño
include("../../conexion.php");
include("../../includes/header.php");

// 2. Consulta de proveedores (Ordenados por Empresa)
$proveedores = mysqli_query($conexion, "SELECT * FROM proveedores ORDER BY empresa ASC");
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
                                    <i class="bi bi-telephone-fill text-primary me-2"></i><?php echo $p['telefono']; ?>
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
                                <a href="eliminar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-white" 
                                   onclick="return confirm('¿Seguro que desea eliminar este proveedor?')" title="Eliminar">
                                    <i class="bi bi-trash3 text-danger"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php 
// 3. Cierre del layout
include("../../includes/footer.php"); 
?>