<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Consulta todos los clientes ordenados alfabéticamente por nombre
$clientes = mysqli_query($conexion, "SELECT * FROM clientes ORDER BY nombre ASC");
?>

<!-- Contenedor principal del módulo de clientes -->
<div class="container-fluid">

    <!-- Encabezado del módulo con botón para registrar un nuevo cliente -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-0 text-dark">Directorio de Clientes</h2>
            <p class="text-muted small">Administra la información de contacto de tus compradores.</p>
        </div>

        <!-- Botón que dirige al formulario para agregar un nuevo cliente -->
        <a href="agregar.php" class="btn btn-primary rounded-pill px-4 shadow-sm">
            <i class="bi bi-person-plus-fill me-2"></i>Registrar Cliente
        </a>
    </div>

    <!-- Tarjeta que contiene la tabla de clientes -->
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

        <!-- Tabla responsiva -->
        <div class="table-responsive">
            <table class="table align-middle mb-0 table-hover">

                <!-- Encabezado de columnas -->
                <thead class="table-light">
                    <tr>
                        <th class="ps-4 py-3 text-uppercase small fw-bold text-muted">Nombre</th>
                        <th class="text-uppercase small fw-bold text-muted">Contacto Directo</th>
                        <th class="text-uppercase small fw-bold text-muted">Email</th>
                        <th class="text-center text-uppercase small fw-bold text-muted">Acciones</th>
                    </tr>
                </thead>

                <!-- Cuerpo de la tabla con los clientes -->
                <tbody>

                    <?php while($c = mysqli_fetch_array($clientes)){ 
                        
                        // Limpia el número telefónico eliminando espacios, guiones o puntos para usarlo en enlaces
                        $tel_limpio = str_replace([' ', '-', '.'], '', $c['telefono']);
                    ?>

                    <tr>

                        <!-- Columna del nombre del cliente -->
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <span class="fw-bold"><?php echo $c['nombre']; ?></span>
                            </div>
                        </td>

                        <!-- Columna de contacto telefónico y WhatsApp -->
                        <td>
                            <div class="d-flex flex-column">

                                <!-- Enlace para realizar llamada telefónica -->
                                <a href="tel:<?php echo $tel_limpio; ?>" class="text-decoration-none text-dark mb-1">
                                    <i class="bi bi-telephone-fill text-primary me-2 small"></i><?php echo $c['telefono']; ?>
                                </a>

                                <!-- Enlace para enviar mensaje por WhatsApp -->
                                <a href="https://wa.me/57<?php echo $tel_limpio; ?>" target="_blank" class="text-success text-decoration-none small fw-bold">
                                    <i class="bi bi-whatsapp me-1"></i> Enviar Mensaje
                                </a>

                            </div>
                        </td>

                        <!-- Columna del correo electrónico -->
                        <td>
                            <a href="mailto:<?php echo $c['email']; ?>" class="text-muted text-decoration-none">
                                <i class="bi bi-envelope me-2"></i><?php echo $c['email']; ?>
                            </a>
                        </td>

                        <!-- Columna de acciones (editar y eliminar) -->
                        <td class="text-center">
                            <div class="btn-group shadow-sm rounded-3">

                                <!-- Botón para editar el cliente -->
                                <a href="editar.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-white border-end" title="Editar">
                                    <i class="bi bi-pencil-square text-primary"></i>
                                </a>

                                <!-- Botón para eliminar el cliente con confirmación -->
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
// Incluye el pie de página del sistema
include("../../includes/footer.php"); 
?>