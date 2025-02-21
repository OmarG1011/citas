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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Lista de Citas Médicas</h2>
        <p class="mt-3 text-center"><a href="../index.php?action=logout">Cerrar sesión</a></p>
        <!-- Botón para Agregar Cita -->
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCitaModal">Agregar Cita</button>
        <table id="citasTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
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
                            <input type="text" class="form-control" id="addDoctor" name="doctor" required>
                        </div>
                        <div class="mb-3">
                            <label for="addFecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="addFecha" name="fecha" required>
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
                            <input type="text" class="form-control" id="editDoctor" name="doctor" required>
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
    <script src="../script.js"></script>

</body>

</html>