<?php

function conectarDB() {
    $servidor = "localhost";
    $usuario = "root";
    $password = "12345"; 
    $bd = "AgronovaMVC";

    $conn = mysqli_connect($servidor, $usuario, $password, $bd);
    
    if (!$conn) {
        die("Error de conexión: " . mysqli_connect_error());
    }

    mysqli_set_charset($conn, "utf8");

    return $conn;
}
class Conexion {
    public static function conectar() {
        return conectarDB();
    }
}