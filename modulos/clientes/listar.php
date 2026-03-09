<?php
// 1. Incluimos los archivos base con rutas relativas
include("../../conexion.php");
include("../../includes/header.php");

// 2. Consulta de clientes ordenada alfabéticamente
$clientes = mysqli_query($conexion, "SELECT * FROM clientes ORDER BY nombre ASC");
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
                    <?php while($c = mysqli_fetch_array($clientes)){ 
                        // Limpiamos el número para el enlace de WhatsApp (eliminamos espacios y guiones)
                        $tel_limpio = str_replace([' ', '-', '.'], '', $c['telefono']);
                    ?>
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <span class="fw-bold"><?php echo $c['nombre']; ?></span>
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
                                   onclick="return confirm('¿Eliminar cliente?')" title="Eliminar">
                                    <i class="bi bi-trash text-danger"></i>
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
// 3. Cerramos el wrapper y cargamos scripts
include("../../includes/footer.php"); 
?>