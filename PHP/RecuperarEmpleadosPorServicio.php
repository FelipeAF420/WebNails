<?php
// Verificar si se recibieron los IDs de los servicios seleccionados
if(isset($_GET['servicios'])) {
    // Obtener los IDs de los servicios seleccionados desde la solicitud AJAX
    $serviciosSeleccionados = $_GET['servicios'];

    // Incluir el archivo de conexión a la base de datos
    include 'Conection.php';

    try {
        // Preparar la consulta SQL para obtener los empleados asociados a los servicios seleccionados
        // Utilizamos INNER JOIN para unir la tabla Empleado con la tabla EmpleadoServicio
        // y WHERE IN para filtrar los empleados que estén asociados a alguno de los servicios seleccionados
        $query = "SELECT DISTINCT e.* FROM Empleado e 
                  INNER JOIN EmpleadoServicio es ON e.id_empleado = es.id_empleado 
                  WHERE es.id_servicio IN (" . implode(',', $serviciosSeleccionados) . ")";
        $statement = $conexion->prepare($query);
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
    // Si no se recibieron los IDs de los servicios seleccionados, devolver un mensaje de error
    echo json_encode(array('error' => 'No se recibieron los IDs de los servicios seleccionados.'));
}
?>
