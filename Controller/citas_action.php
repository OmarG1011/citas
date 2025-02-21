<?php
session_start(); // Asegúrate de iniciar la sesión

include_once '../model/BD.php';
include_once '../model/Citas.php';

$database = new Database();
$db = $database->getConnection();
$citas = new Citas($db);

if ($_POST['action'] == 'listCitas') {
    // Obtén el user_id del usuario que ha iniciado sesión
    $user_id = $_SESSION['user_id'];

    // Obtener las citas del usuario
    $citasList = $citas->listCitas($user_id); // Asegúrate de que tu método acepte el user_id

    // Devolver las citas en formato JSON
    echo json_encode($citasList);
}

if ($_POST['action'] == 'getCita') {
    $id = $_POST['id'];
    $stmt = $db->prepare("SELECT * FROM citas WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $cita = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($cita);
}

if ($_POST['action'] == 'updateCita') {
    $id = $_POST['id'];
    $paciente = $_POST['paciente'];
    $doctor = $_POST['doctor'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    $stmt = $db->prepare("UPDATE citas SET paciente = :paciente, doctor = :doctor, fecha = :fecha, hora = :hora WHERE id = :id");
    $stmt->bindParam(':paciente', $paciente);
    $stmt->bindParam(':doctor', $doctor);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':hora', $hora);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cita actualizada con éxito.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la cita.']);
    }
}

if ($_POST['action'] == 'deleteCita') {
    $id = $_POST['id'];
    $stmt = $db->prepare("DELETE FROM citas WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cita eliminada con éxito.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la cita.']);
    }
}

if ($_POST['action'] == 'addCita') {
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario que ha iniciado sesión
    $paciente = $_POST['paciente'];
    $doctor = $_POST['doctor'];
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];

    $stmt = $db->prepare("INSERT INTO citas (user_id, paciente, doctor, fecha, hora) VALUES (:user_id, :paciente, :doctor, :fecha, :hora)");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':paciente', $paciente);
    $stmt->bindParam(':doctor', $doctor);
    $stmt->bindParam(':fecha', $fecha);
    $stmt->bindParam(':hora', $hora);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Cita agregada con éxito.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error al agregar la cita.']);
    }
}

if ($_POST['action'] == 'getDoctores') {
    // Depuración para asegurarnos de que la acción se recibe
    error_log('Action getDoctores recibida');
    $stmt = $db->prepare("SELECT nombre FROM doctores");
    $stmt->execute();
    $doctores = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Verifica si se obtienen doctores
    if ($doctores) {
        echo json_encode($doctores);  // Enviar respuesta en caso de éxito
    } else {
        echo json_encode(['error' => 'No se encontraron doctores']);
    }
}