<?php

require_once __DIR__ . '/../config/conexion.php';

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar(); 
    }

    public function verificarCredenciales($username, $password) {
        $userLimpio = mysqli_real_escape_string($this->db, $username);
        
        $sql = "SELECT id_usuario, nombre_completo, password, rol FROM usuarios WHERE username = '$userLimpio' LIMIT 1";
        
        $resultado = mysqli_query($this->db, $sql);

        if ($resultado) {
            if ($usuario = mysqli_fetch_array($resultado)) {
                
                if ($password === $usuario['password']) {
                    return $usuario; 
                }
            }
        }
        
        return false;
    }
}