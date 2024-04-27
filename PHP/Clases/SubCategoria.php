<?php
class Subcategoria {
    private $id;
    private $idCategoria;
    private $nombre;
    private $imagen;

    public function __construct($idCategoria, $nombre, $imagen) {
        $this->idCategoria = $idCategoria;
        $this->nombre = $nombre;
        $this->imagen = $imagen;
    }

    // Métodos getters
    public function getId() {
        return $this->id;
    }

    public function getIdCategoria() {
        return $this->idCategoria;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function setId($id){
        $this->id = $id;
    }
    // Métodos setters
    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    // Método toString
    public function __toString() {
        return "ID Subcategoría: {$this->id}, ID Categoría: {$this->idCategoria}, Nombre: {$this->nombre}";
    }
}

?>