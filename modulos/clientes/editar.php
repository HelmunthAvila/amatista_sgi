<?php
// Incluye la conexión a la base de datos y el encabezado general del sistema
include("../../conexion.php");
include("../../includes/header.php");

// Obtiene el ID del cliente enviado desde el listado de forma segura
$id = intval($_GET['id']);

// Consulta la información del cliente seleccionado
$query = mysqli_query($conexion, "SELECT * FROM clientes WHERE id = $id");
$c = mysqli_fetch_assoc($query);

if(!$c) {
    echo "<div class='container mt-4'><div class='alert alert-danger'>Cliente no encontrado.</div></div>";
    include("../../includes/footer.php");
    exit();
}
?>

<style>
    :root {
        --primary-color: #512da8;
        --primary-hover: #432293;
        --border-radius-card: 16px;
    }
    .btn-amatista-primary {
        background-color: var(--primary-color) !important;
        border-color: var(--primary-color) !important;
        color: #ffffff !important;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .btn-amatista-primary:hover {
        background-color: var(--primary-hover) !important;
        border-color: var(--primary-hover) !important;
        transform: translateY(-1px);
    }
    .card-custom {
        border-radius: var(--border-radius-card) !important;
        border: none !important;
        background-color: #ffffff;
    }
    .form-control-custom {
        border-radius: 10px !important;
        border: 1px solid #ced4da !important;
        padding: 0.6rem 1rem;
    }
    .form-control-custom:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(81, 45, 168, 0.15) !important;
    }
</style>

<div class="container-fluid px-4 py-3">

    <div class="mb-4">
        <a href="listar.php" class="btn btn-link text-decoration-none text-muted p-0 mb-2 small fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>
        <h2 class="fw-bold text-dark" style="letter-spacing: -0.5px;">Editar Información del Cliente</h2>
        <p class="text-muted small">
            Modifique los campos necesarios para actualizar al cliente: 
            <strong><?php echo htmlspecialchars($c['nombre']); ?></strong>
        </p>
    </div>

    <div class="row">
        <div class="col-xl-5 col-lg-7">
            <div class="card card-custom shadow-sm p-4">
                <form action="actualizar.php" method="POST">

                    <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Nombre Completo</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-person"></i></span>
                            <input type="text" name="nombre" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   value="<?php echo htmlspecialchars($c['nombre']); ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Teléfono / Celular</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-telephone"></i></span>
                            <input type="text" name="telefono" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   value="<?php echo htmlspecialchars($c['telefono']); ?>">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-semibold text-secondary text-uppercase">Correo Electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border text-muted"><i class="bi bi-envelope"></i></span>
                            <input type="email" name="email" class="form-control form-control-custom" style="border-radius: 0 10px 10px 0 !important;"
                                   value="<?php echo htmlspecialchars($c['email']); ?>">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-amatista-primary rounded-pill py-3 shadow-sm">
                            <i class="bi bi-save-fill me-2"></i>Actualizar Cambios
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include("../../includes/footer.php"); ?>