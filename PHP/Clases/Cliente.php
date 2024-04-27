<?php
class Cliente {
    private $nombre;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $correo;
    private $contrasenaHash; // Almacenar el hash de la contraseña en lugar de la contraseña en texto plano
    private $numCelular;
    private $imagen;
    private $asistencias;
    private $faltas;
    private $cancelaciones;

    public function __construct($nombre, $apellidoPaterno, $apellidoMaterno, $correo, $contrasena, $numCelular, $imagen, $asistencias, $faltas, $cancelaciones) {
        $this->nombre = $nombre;
        $this->apellidoPaterno = $apellidoPaterno;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->correo = $correo;
        $this->contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT); // Hash de la contraseña
        $this->numCelular = $numCelular;
        $this->imagen = $imagen;
        $this->asistencias = $asistencias;
        $this->faltas = $faltas;
        $this->cancelaciones = $cancelaciones;
    }

    // Métodos getters
    public function getNombre() {
        return $this->nombre;
    }

    public function getApellidoPaterno() {
        return $this->apellidoPaterno;
    }

    public function getApellidoMaterno() {
        return $this->apellidoMaterno;
    }

    public function getCorreo() {
        return $this->correo;
    }

    public function getContrasenaHash() {
        return $this->contrasenaHash;
    }

    public function getNumCelular() {
        return $this->numCelular;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function getAsistencias() {
        return $this->asistencias;
    }

    public function getFaltas() {
        return $this->faltas;
    }

    public function getCancelaciones() {
        return $this->cancelaciones;
    }

    // Métodos setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setApellidoPaterno($apellidoPaterno) {
        $this->apellidoPaterno = $apellidoPaterno;
    }

    public function setApellidoMaterno($apellidoMaterno) {
        $this->apellidoMaterno = $apellidoMaterno;
    }

    public function setCorreo($correo) {
        $this->correo = $correo;
    }

    public function setContrasena($contrasena) {
        $this->contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT); // Actualizar el hash de la contraseña
    }

    public function setNumCelular($numCelular) {
        $this->numCelular = $numCelular;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    public function setAsistencias($asistencias) {
        $this->asistencias = $asistencias;
    }

    public function setFaltas($faltas) {
        $this->faltas = $faltas;
    }

    public function setCancelaciones($cancelaciones) {
        $this->cancelaciones = $cancelaciones;
    }

    // Método para verificar la contraseña
    public function verificarContrasena($contrasena) {
        return password_verify($contrasena, $this->contrasenaHash);
    }

    // Método toString
    public function __toString() {
        return "Nombre: {$this->nombre} {$this->apellidoPaterno} {$this->apellidoMaterno}, Correo: {$this->correo}, Número Celular: {$this->numCelular}";
    }
}

?>
