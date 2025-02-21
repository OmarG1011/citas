<?php
class User
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // public function register($username, $password)
    // {
    //     $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    //     $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    //     $stmt->bindParam(':username', $username);
    //     $stmt->bindParam(':password', $hashed_password);
    //     return $stmt->execute();
    // }
    public function usernameExists($username) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $count = $stmt->fetchColumn(); // Obtiene el número de registros con ese nombre de usuario
        return $count > 0; // Retorna true si el usuario ya existe, false si no
    }
    public function register($username, $password)
    {
        // Verificar si el nombre de usuario ya existe
        if ($this->usernameExists($username)) {
            // Si el nombre de usuario ya existe, lanzamos un error o retornamos false
            return false; // O podrías lanzar una excepción si prefieres
        }
    
        // Si el nombre de usuario no existe, procedemos a registrarlo
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        
        // Retornar true si la inserción es exitosa, false en caso contrario
        return $stmt->execute();
    }
        

    // En tu modelo de usuario (userModel.php)
    public function login($username, $password)
    {
        $query = "SELECT id, password FROM users WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user['id']; // Devuelve el user_id
        }
        return false; // Usuario o contraseña incorrectos
    }
}
