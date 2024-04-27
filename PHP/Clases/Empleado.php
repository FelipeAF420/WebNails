<?php
class Empleado {

    private $nombre;
    private $apellidoPaterno;
    private $apellidoMaterno;
    private $imagen;

    public function __construct($nombre, $apellidoPaterno, $apellidoMaterno, $imagen) {
        $this->nombre = $nombre;
        $this->apellidoPaterno = $apellidoPaterno;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->imagen = $imagen;
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

    public function getImagen() {
        return $this->imagen;
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

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    // Método toString
    public function __toString() {
        return "Nombre: {$this->nombre} {$this->apellidoPaterno} {$this->apellidoMaterno}";
    }
}
?>

