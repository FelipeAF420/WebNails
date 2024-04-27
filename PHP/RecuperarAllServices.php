<?php
// Importa la conexión a la base de datos
require_once 'Conection.php';

try {
    // Query para obtener todos los servicios
    $query = "SELECT * FROM Servicio";

    // Prepara la consulta
    $statement = $conexion->prepare($query);

    // Ejecuta la consulta
    $statement->execute();

    // Obtiene todos los servicios como un array asociativo
    $servicios = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Devuelve los servicios como JSON
    echo json_encode($servicios);
} catch (PDOException $e) {
    // Manejo de errores si la consulta falla
    echo json_encode(array('error' => 'No se pudieron obtener los servicios'));
}
?>
