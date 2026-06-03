<?php
// Activar reportes de error para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../config/conexion.php';

class Cliente {
    private $db;

    public function __construct() {
        $this->db = Conexion::conectar();
    }
    public function listarTodos() {
        $sql = "SELECT * FROM clientes";
        return mysqli_query($this->db, $sql);
    }
    //funcion de busqueda
    public function existeNit($ci_nit) {
        $nitEsc = mysqli_real_escape_string($this->db, $ci_nit);
        $sql = "SELECT id_cliente FROM clientes WHERE ci_nit = '$nitEsc' LIMIT 1";
        $resultado = mysqli_query($this->db, $sql);
        if ($resultado && mysqli_num_rows($resultado) > 0) {
            return true;
        }
        return false;
    }
    //CREATE
    public function crear($nombre, $ci_nit, $telefono, $direccion, $zona) {
        $nombreEsc = mysqli_real_escape_string($this->db, $nombre);
        $nitEsc = mysqli_real_escape_string($this->db, $ci_nit);
        $telEsc = mysqli_real_escape_string($this->db, $telefono);
        $dirEsc = mysqli_real_escape_string($this->db, $direccion);
        $zonaEsc = mysqli_real_escape_string($this->db, $zona);

        $sql = "INSERT INTO clientes (nombre_completo, ci_nit, telefono, direccion, zona) 
                VALUES ('$nombreEsc', '$nitEsc', '$telEsc', '$dirEsc', '$zonaEsc')";
        
        return mysqli_query($this->db, $sql);
    }
    //READ por ID
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM clientes WHERE id_cliente = $id LIMIT 1";
        $resultado = mysqli_query($this->db, $sql);
        return mysqli_fetch_array($resultado);
    }
    //UPDATE
    public function actualizar($id, $nombre, $ci_nit, $telefono, $direccion, $zona) {
        $nombreEsc = mysqli_real_escape_string($this->db, $nombre);
        $nitEsc = mysqli_real_escape_string($this->db, $ci_nit);
        $telEsc = mysqli_real_escape_string($this->db, $telefono);
        $dirEsc = mysqli_real_escape_string($this->db, $direccion);
        $zonaEsc = mysqli_real_escape_string($this->db, $zona);

        $sql = "UPDATE clientes SET 
                nombre_completo = '$nombreEsc', 
                ci_nit = '$nitEsc', 
                telefono = '$telEsc', 
                direccion = '$dirEsc', 
                zona = '$zonaEsc' 
                WHERE id_cliente = $id";
        
        return mysqli_query($this->db, $sql);
    }
}