<?php
// Verificar si se recibió el ID del servicio
if(isset($_GET['servicio'])) {
    // Obtener el ID del servicio desde la solicitud AJAX
    $servicioId = $_GET['servicio'];

    // Incluir el archivo de conexión a la base de datos
    include 'Conection.php';

    try {
        // Consulta SQL para obtener los empleados asociados al servicio
        $query = "SELECT e.id_empleado, e.nombre, e.apellidoP, e.apellidoM, e.imagen 
                  FROM Empleado e
                  INNER JOIN EmpleadoServicio es ON e.id_empleado = es.id_empleado
                  WHERE es.id_servicio = :servicioId";
                  
        $statement = $conexion->prepare($query);
        $statement->bindParam(':servicioId', $servicioId, PDO::PARAM_INT);
        $statement->execute();

        // Obtener los resultados de la consulta como un array asociativo
        $empleados = $statement->fetchAll(PDO::FETCH_ASSOC);

        // Devolver los empleados en formato JSON
        echo json_encode($empleados);
    } catch(PDOException $e) {
        // En caso de error, devolver un mensaje de error
        echo json_encode(array('error' => 'Error al obtener los empleados: ' . $e->getMessage()));
    }
} else {
    // Si no se recibió el ID del servicio, devolver un mensaje de error
    echo json_encode(array('error' => 'No se recibió el ID del servicio.'));
}
?>
