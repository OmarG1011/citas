<?php
class Citas {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para listar todas las citas
    public function listCitas($user_id) {
        $sql = "SELECT * FROM citas WHERE user_id = :user_id"; // Filtrar por user_id
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCitasPorUsuario($user_id) {
        $query = "SELECT * FROM citas WHERE user_id = :user_id"; // Cambia appointments a citas
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Método para obtener doctores
  public function getDoctors() {
    $query = "SELECT nombre name FROM doctors"; // Adjust the query based on your database structure
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
    
}
?>