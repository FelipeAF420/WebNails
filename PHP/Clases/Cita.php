<?php
class Cita {
    private $idCita;
    private $idCliente;
    private $fecha;
    private $hora;

    public function __construct($idCliente, $fecha, $hora) {
        $this->idCliente = $idCliente;
        $this->fecha = $fecha;
        $this->hora = $hora;
    }

    // Métodos getters
    public function getIdCita() {
        return $this->idCita;
    }

    public function getIdCliente() {
        return $this->idCliente;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getHora() {
        return $this->hora;
    }

    // Métodos setters
    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setHora($hora) {
        $this->hora = $hora;
    }

    // Método toString
    public function __toString() {
        return "ID Cita: {$this->idCita}, ID Cliente: {$this->idCliente}, Fecha: {$this->fecha}, Hora: {$this->hora}";
    }
}

?>