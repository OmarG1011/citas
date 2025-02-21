<?php
class Database {
    private $host = "localhost";
    private $db_name = "CitasMedicas";
    private $user = "omar";
    private $pass = "12345678";
    private $port = "1433";
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("sqlsrv:server=$this->host,$this->port;Database=$this->db_name", $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

        return $this->conn;
    }
}
?>