<?php
// Obtener la conexión a la base de datos (debes configurar esto según tu entorno)
include 'Conection.php';

// Verificar si se proporcionó el ID del empleado en la solicitud GET
if(isset($_GET['idEmpleado'])) {
    // Obtener el ID del empleado de la solicitud GET
    $idEmpleado = $_GET['idEmpleado'];

    // Consultar la base de datos para obtener los horarios del empleado
    $query = "SELECT * FROM HorarioEmpleado WHERE id_empleado = :idEmpleado";
    $statement = $conexion->prepare($query);
    $statement->bindParam(':idEmpleado', $idEmpleado);
    $statement->execute();

    // Obtener los resultados de la consulta como un array asociativo
    $horarios = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los horarios como respuesta en formato JSON
    echo json_encode($horarios);
} else {
    // Si no se proporcionó el ID del empleado, devolver un mensaje de error
    echo json_encode(array('mensaje' => 'ID de empleado no proporcionado'));
}
?>
