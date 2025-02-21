<?php
session_start();
require_once 'model/BD.php'; // Asegúrate de que la ruta sea correcta
require_once 'controller/autentificar.php'; // Asegúrate de que la ruta sea correcta

$controller = new AuthController();

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'registro':
            $controller->register();
            break;
        case 'login':
            $controller->login();
            break;
        case 'logout':
            $controller->logout(); // Llama al método de logout
            break;
        default:
            include 'view/login.php'; // Cargar la vista de login por defecto
            break;
    }
} else {
    include 'view/login.php'; // Cargar la vista de login por defecto
}
?>