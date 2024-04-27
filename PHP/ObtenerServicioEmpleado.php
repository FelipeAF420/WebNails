<?php
// Incluir el archivo de conexión a la base de datos
require_once 'Conection.php';

// Verificar si se recibió el ID del empleado por GET
if(isset($_GET['idEmpleado'])) {
    // Obtener el ID del empleado
    $idEmpleado = $_GET['idEmpleado'];

    // Consulta SQL para obtener los servicios del empleado con su imagen
    $query = "SELECT s.id_servicio, s.nombre, s.imagen
              FROM Servicio s
              INNER JOIN EmpleadoServicio es ON s.id_servicio = es.id_servicio
              WHERE es.id_empleado = ?";
    
    // Preparar la consulta
    $stmt = $conexion->prepare($query);

    // Vincular el parámetro ID del empleado
    $stmt->bindParam(1, $idEmpleado);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados de la consulta
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los resultados como JSON
    echo json_encode($resultados);
} else {
    // Si no se recibió el ID del empleado, devolver un mensaje de error
    echo json_encode(array('error' => 'No se proporcionó el ID del empleado.'));
}
?>
