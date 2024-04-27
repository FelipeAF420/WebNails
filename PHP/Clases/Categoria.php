<?php
class Categoria {
    private $idCategoria;
    private $nombre;
    private $imagen;

    public function __construct($nombre, $imagen) {
        $this->nombre = $nombre;
        $this->imagen = $imagen;
    }

    // Métodos getters
    public function getId() {
        return $this->idCategoria;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setId($idCategoria){
        $this->idCategoria = $idCategoria;
    }
    // Métodos setters
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    // Método toString
    public function __toString() {
        return "ID Categoria: {$this->idCategoria}, Nombre: {$this->nombre}";
    }
}

?>