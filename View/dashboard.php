<?php
session_start();
if (!isset($_SESSION['user_id'])) { // Verifica si el user_id está en la sesión
    header("Location: index.php?action=login"); // Redirige al login si no hay sesión activa
    exit();
}

include('../model/BD.php');
include('../model/citas.php');

$database = new Database();
$db = $database->getConnection();
$citas = new Citas($db);

// Obtén el user_id del usuario que ha iniciado sesión
$user_id = $_SESSION['user_id'];

// Obtén las citas del usuario
$citasList = $citas->getCitasPorUsuario($user_id);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- SweetAlert2 CSS -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">

    <link rel="stylesheet" href="../styles.css"> <!-- Archivo con animación -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container-fluid white text-white d-flex justify-content-center align-items-center mb-3">
        <div class="container mt-5">
            <h2 class="text-center"><?php
                                    // Verifica que el username esté en la sesión antes de usarlo
                                    if (isset($_SESSION['username'])) {
                                        echo "Bienvenido, " . $_SESSION['username']; // Muestra el nombre del usuario
                                    } else {
                                        echo "Bienvenido, usuario desconocido"; // En caso de que no haya nombre de usuario en la sesión
                                    }
                                    ?> </h2>
            <p class="mt-3 text-center"><a href="../index.php?action=logout" class='text-white'>Cerrar sesión</a></p>
            <!-- Botón para Agregar Cita -->
            <button class="btn btn-success mb-3 float-end" data-bs-toggle="modal" data-bs-target="#addCitaModal">Agregar Cita</button>
        </div>
    </div>


    <table id="citasTable" class="table table-striped">
        <thead>
            <tr>
                <th>Paciente</th>
                <th>Doctor</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <!-- Los datos se llenarán mediante AJAX -->
        </tbody>
    </table>


    </div>
    </div>
    <!-- Modal para Agregar Cita -->
    <div class="modal fade" id="addCitaModal" tabindex="-1" aria-labelledby="addCitaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCitaModalLabel">Agregar Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addCitaForm">
                        <div class="mb-3">
                            <label for="addPaciente" class="form-label">Paciente</label>
                            <input type="text" class="form-control" id="addPaciente" name="paciente" required>
                        </div>
                        <div class="mb-3">
                            <label for="addDoctor" class="form-label">Doctor</label>
                            <select class="form-control" id="addDoctor" name="doctor" required>
                                <option value="">Seleccione un doctor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="addFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="addFecha" name="fecha" required max="9999-12-31">

                        </div>
                        <div class="mb-3">
                            <label for="addHora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="addHora" name="hora" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Agregar Cita</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal para Editar Cita -->
    <div class="modal fade" id="editCitaModal" tabindex="-1" aria-labelledby="editCitaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCitaModalLabel">Editar Cita</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCitaForm">
                        <input type="hidden" id="editCitaId" name="id">
                        <div class="mb-3">
                            <label for="editPaciente" class="form-label">Paciente</label>
                            <input type="text" class="form-control" id="editPaciente" name="paciente" required>
                        </div>
                        <div class="mb-3">
                            <label for="editDoctor" class="form-label">Doctor</label>
                            <select class="form-control" id="editDoctor" name="doctor" required>
                                <option value="">Seleccione un doctor</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="editFecha" name="fecha" required>
                        </div>
                        <div class="mb-3">
                            <label for="editHora" class="form-label">Hora</label>
                            <input type="time" class="form-control" id="editHora" name="hora" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

    <script src="../script.js"></script>

    <?php if (isset($_SESSION['sweet_alert'])): ?>
        <script>
            Swal.fire({
                icon: '<?php echo $_SESSION['sweet_alert']['type']; ?>',
                title: '<?php echo $_SESSION['sweet_alert']['message']; ?>',
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    <?php unset($_SESSION['sweet_alert']);
    endif; ?>
</body>

</html>