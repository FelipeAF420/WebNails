<?php
class Servicio {
    private $id;
    private $idSubcategoria;
    private $nombre;
    private $descripcion;
    private $precio;
    private $imagen;

    public function __construct($idSubcategoria, $nombre, $descripcion, $precio, $imagen) {
        $this->idSubcategoria = $idSubcategoria;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
        $this->precio = $precio;
        $this->imagen = $imagen;
    }

    // Métodos getters

    
    public function getId() {
        return $this->id;
    }   

    public function getIdSubcategoria() {
        return $this->idSubcategoria;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getDescripcion() {
        return $this->descripcion;
    }

    public function getPrecio() {
        return $this->precio;
    }

    public function getImagen() {
        return $this->imagen;
    }

    // Métodos setters
    public function setId($id) {
        $this->id = $id;
    }
    public function setIdSubCategoria($idSubcategoria) {
        $this->$idSubcategoria = $idSubcategoria;
    }
    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setPrecio($precio) {
        $this->precio = $precio;
    }

    public function setImagen($imagen) {
        $this->imagen = $imagen;
    }

    // Método toString
    public function __toString() {
        return "Nombre: {$this->nombre}, Descripción: {$this->descripcion}, Precio: {$this->precio}";
    }
}

?>
