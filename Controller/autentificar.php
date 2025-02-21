<?php
require_once 'model/BD.php'; // Asegúrate de que la ruta sea correcta
require_once 'model/User.php'; // Asegúrate de que la ruta sea correcta

class AuthController {
    private $userModel;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->userModel = new User($db);
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            if ($this->userModel->register($username, $password)) {
                header("Location: index.php?action=login");
                exit();
            } else {
                $error_message = "Error al registrar el usuario (nombre invalido o ya en uso)";
            }
        }
        include 'view/registro.php'; // Asegúrate de que la vista exista
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
    
            // Obtén el user_id al iniciar sesión
            $user_id = $this->userModel->login($username, $password);
            if ($user_id) {
                session_start();
                $_SESSION['user_id'] = $user_id; // Almacena el user_id en la sesión
                $_SESSION['username'] = $username; // Almacena el username en la sesión
                header("Location: view/dashboard.php"); // Asegúrate de que la vista exista
                exit();
            } else {
                $error_message = "Usuario o contraseña incorrectos.";
            }
        }
        include 'view/login.php'; // Cargar la vista de login
    }
    public function logout() {
        session_start(); // Inicia la sesión
        session_destroy(); // Destruye la sesión
        header("Location: ./index.php?action=login"); // Redirige al login
        exit(); // Asegúrate de salir después de la redirección
    }
}
?>