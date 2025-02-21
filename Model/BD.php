<?php
class Database {
    private $host = "localhost"; // Dirección del servidor
    private $db_name = "CitasMedicas"; // Nombre de la base de datos
    private $user = "root"; // Usuario de MySQL
    private $pass = ""; // Contraseña de MySQL
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            // Cambiar la cadena de conexión para MySQL
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establecer el modo de error
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage()); // En caso de error, mostrar mensaje
        }

        return $this->conn;
    }
}
?>
